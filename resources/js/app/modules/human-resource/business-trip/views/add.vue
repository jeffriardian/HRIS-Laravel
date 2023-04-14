<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $tc('create') }} {{ $tc('business trip') }}</h5>
    </div>
    <div class="card-body">
      <formComponent :isProcess="isLoadingProcess"></formComponent>
    </div>
    <div class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center">
      <div class="mt-2 mt-sm-0">
        <button @click.prevent="submit" class="btn btn-primary" :disabled="isLoadingProcess">
          <b-spinner v-if="isLoadingProcess" small class="mr-2"></b-spinner>
          <i class="far fa-save mr-2"></i>
          {{ $t('save') }}
        </button>
        <router-link to="/human-resource/business-trips" class="btn btn-light ml-2">
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
  data() {
    return {
      isLoadingProcess: false,
    };
  },
  methods: {
    ...mapActions("businessTrip", ["store"]), //PANGGIL ACTIONS store
    submit() {
      //KETIKA TOMBOL DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
      //DIMANA FUNGSI INI TELAH DIBUAT PADA SESI SEBELUMNYA
      this.isLoadingProcess = true;
      this.store(this.form).then(() => {
        //APABILA BERHASIL MAKA AKAN DI-DIRECT KE HALAMAN INDEX
        this.$router.push({ name: "businessTrips.index" });
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("Business Trip "),
            message: this.$tc("add", 2),
          }),
        });
      });
    },
  },
  computed: {
    ...mapState("businessTrip", {
      form: (state) => state.form, //MENGAMBIL STATE DATA
    }),
    ...mapState(["errors"]),
  },
  watch: {
    errors() {
      if (typeof this.errors === "object") {
        if (Object.keys(this.errors).length > 0) {
          this.isLoadingProcess = false;
        }
      }
    },
  },
  components: {
    formComponent,
  },
};
</script>
