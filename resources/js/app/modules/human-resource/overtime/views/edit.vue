<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $tc('update') }} {{ $tc('overtime') }}</h5>
    </div>
    <div class="card-body">
      <formComponent></formComponent>
    </div>
    <div class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center">
      <div class="mt-2 mt-sm-0">
        <button @click.prevent="submit" class="btn btn-primary" :disabled="loadingProcess">
          <b-spinner v-if="loadingProcess" small class="mr-2"></b-spinner>
          <i v-if="!loadingProcess" class="far fa-save mr-2"></i>
          {{ $t('save') }}
        </button>
        <router-link
          to="/human-resource/overtimes"
          class="btn btn-light ml-2"
          v-if="!loadingProcess"
        >
          <i class="fas fa-arrow-right mr-2"></i>
          {{ $t('cancel') }}
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
import formComponent from "./form.vue";
export default {
  mixins: ["calculateTotalDuration"],
  components: {
    formComponent,
  },
  data() {
    return {
      loadingProcess: false,
    };
  },
  created() {
    //SEBELUM COMPONENT DI-RENDER KITA MELAKUKAN REQUEST KESERVER
    //BERDASARKAN ID YANG ADA DI URL
    this.show(this.$route.params.id);
  },
  computed: {
    ...mapState(["errors"]),
    ...mapState("overtime", {
      form: (state) => state.form,
    }),
  },
  watch: {
    errors() {
      if (typeof this.errors == "object") {
        if (Object.keys(this.errors).length > 0) {
          this.loadingProcess = false;
        }
      }
    },
  },
  methods: {
    ...mapActions("overtime", ["show", "update"]),
    submit() {
      //KETIKA TOMBOL UPDATE DI MAKA AKAN MENGIRIMKAN PERMINTAAN
      //UNTUK MENGUBAH DATA BERDASARKAN ID YANG DIKIRIMKAN
      this.loadingProcess = true;
      this.form.total_time = this.totalTime(
        this.form.overtime_before_duration,
        this.form.overtime_after_duration
      );
      let form = new FormData();
      for (const key in this.form) {
        if (key === "attachment") {
          if (typeof this.form[key] === "object") {
            form.append(`${key}`, this.form[key]);
          }
        } else {
          form.append(`${key}`, this.form[key]);
        }
      }
      form.append("_method", "PATCH");

      this.update({ id: this.$route.params.id, data: form }).then(() => {
        //APABILA BERHASIL MAKA AKAN DI-DIRECT KEMBALI KE HALAMAN INDEX
        this.$router.push({ name: "overtimes.index" });
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("overtime"),
            message: this.$tc("update", 2),
          }),
        });
      });
    },
    totalTime(startTime, endTime) {
      const time = [startTime, endTime];
      const checkNull = time.indexOf(null);

      let start;
      let end;
      if (checkNull == -1) {
        start = startTime.split(":");
        end = endTime.split(":");
      } else {
        if (checkNull == 0) {
          start = ["00", "00"];
          if (time[1] == null) {
            end = ["00", "00"];
          } else {
            end = endTime.split(":");
          }
        } else {
          start = startTime.split(":");
          end = ["00", "00"];
        }
      }

      const startTimeHour = parseInt(start[0]);
      const startTimeMinutes = parseInt(start[1]);

      const endTimeHour = parseInt(end[0]);
      const endTimeMinutes = parseInt(end[1]);

      let totalHour = startTimeHour + endTimeHour;
      let totalMinutes = startTimeMinutes + endTimeMinutes;

      if (totalMinutes == 60) {
        totalHour += 1;
        totalMinutes = 0;
      }

      return `${totalHour}.${totalMinutes}`;
    },
  },
};
</script>
