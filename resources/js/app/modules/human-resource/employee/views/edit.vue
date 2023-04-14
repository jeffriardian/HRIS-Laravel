<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $tc('update') }} {{ $tc('employee') }}</h5>
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
  created() {
    //SEBELUM COMPONENT DI-RENDER KITA MELAKUKAN REQUEST KESERVER
    //BERDASARKAN ID YANG ADA DI URL
    this.show(this.$route.params.id);
  },
  methods: {
    ...mapActions("employee", ["show", "update"]),
    submit() {
      //KETIKA TOMBOL UPDATE DI MAKA AKAN MENGIRIMKAN PERMINTAAN
      //UNTUK MENGUBAH DATA BERDASARKAN ID YANG DIKIRIMKAN
      this.isLoadingProcess = true;
      let form = new FormData();
      for (const property in this.form) {
        if (property == "contacts") {
          form.append(`${property}`, JSON.stringify(this.form[property]));
        } else {
          form.append(`${property}`, this.form[property]);
        }
      }
      form.append("_method", "Patch");
      this.$store.commit("employee/SET_ID", this.$route.params.id);
      this.update(form).then(() => {
        //APABILA BERHASIL MAKA AKAN DI-DIRECT KEMBALI KE HALAMAN INDEX
        this.$router.push({ name: "employees.index" });
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("employee"),
            message: this.$tc("update", 2),
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