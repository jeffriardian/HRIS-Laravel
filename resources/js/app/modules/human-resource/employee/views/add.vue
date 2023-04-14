<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $tc('create') }} {{ $tc('employee') }}</h5>
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
        <router-link to="/human-resource/employees" class="btn btn-light ml-2">
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
    ...mapActions("employee", ["store"]), //PANGGIL ACTIONS store
    submit() {
      //KETIKA TOMBOL DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
      //DIMANA FUNGSI INI TELAH DIBUAT PADA SESI SEBELUMNYA
      this.isLoadingProcess = true;
      let form = new FormData();
      for (const property in this.form) {
        if (property == "contacts") {
          form.append(`${property}`, JSON.stringify(this.form[property]));
        } else {
          form.append(`${property}`, this.form[property]);
        }
      }
      this.store(form).then(() => {
        //APABILA BERHASIL MAKA AKAN DI-DIRECT KE HALAMAN INDEX
        this.$router.push({ name: "employees.index" });
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("employee"),
            message: this.$tc("add", 2),
          }),
        });
      });
    },
  },
  components: {
    formComponent,
  },
  computed: {
    ...mapState("employee", {
      form: (state) => state.form,
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
};
</script>