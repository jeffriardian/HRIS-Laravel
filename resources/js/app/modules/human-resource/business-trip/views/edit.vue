<template>
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">{{ $tc('update') }} {{ $tc('business trip') }}</h5>
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
import { mapActions, mapState, mapMutations } from "vuex";
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
    this.show({ id: this.$route.params.id, action: "edit" });
  },
  methods: {
    ...mapActions("businessTrip", ["show", "update"]),
    ...mapMutations(["CLEAR_ERRORS"]),
    submit() {
      this.CLEAR_ERRORS();
      this.isLoadingProcess = true;
      const validation = this.validation();
      if (!validation) {
        let form = new FormData();
        for (const property in this.form) {
          if (property == "parameters") {
            this.form.parameters.forEach((element, index) => {
              if (element.images != null) {
                for (
                  let i = 0;
                  i < this.form.parameters[index].images.length;
                  i++
                ) {
                  const element = this.form.parameters[index].images[i];
                  form.append(`parameters[${index}][images][${i}]`, element);
                }
              }
              let json = JSON.stringify({
                business_trip_id: element.business_trip_id,
                cost: element.cost,
                created_by: element.created_by,
                deleted_at: element.deleted_at,
                dimensions: element.dimensions,
                id: element.id,
                is_active: element.is_active,
                receipt_parameter_id: element.receipt_parameter_id,
                updated_by: element.updated_by,
              });
              form.append(`parameters[${index}][data]`, json);
            });
          } else if (property == "transportations") {
            this.form.transportations.forEach((element, index) => {
              if (element.images != null) {
                for (
                  let i = 0;
                  i < this.form.transportations[index].images.length;
                  i++
                ) {
                  const element = this.form.transportations[index].images[i];
                  form.append(
                    `transportations[${index}][images][${i}]`,
                    element
                  );
                }
              }
              let json = JSON.stringify({
                business_trip_id: element.business_trip_id,
                cost: element.cost,
                created_by: element.created_by,
                deleted_at: element.deleted_at,
                dimensions: element.dimensions,
                id: element.id,
                is_active: element.is_active,
                transportation_id: element.transportation_id,
                updated_by: element.updated_by,
              });
              form.append(`transportations[${index}][data]`, json);
            });
          } else {
            if (this.form[property] != null) {
              form.append(`${property}`, this.form[property]);
            } else {
              if (property == "reimburse_type") {
                form.append(`${property}`, "");
              }
            }
          }
        }
        form.append("_method", "patch");
        this.update({ id: this.$route.params.id, data: form }).then(() => {
          //APABILA BERHASIL MAKA AKAN DI-DIRECT KEMBALI KE HALAMAN INDEX
          this.$router.push({ name: "businessTrips.index" });
          this.$message({
            type: "success",
            message: this.$t("present perfect", {
              object: this.$tc("business trip"),
              message: this.$tc("update", 2),
            }),
          });
        });
      } else {
        this.isLoadingProcess = false;
      }
    },
    validation() {
      let errors = false;

      let listErrors = {};

      this.form.parameters.forEach((element, index) => {
        if (
          element.receipt_parameter_id == 0 ||
          element.receipt_parameter_id == null
        ) {
          errors = true;
          const key = `parameters.${index}.receipt_parameter_id`;
          Object.assign(listErrors, {
            [key]: ["The allowance field is required."],
          });
        }
        if (element.cost == null) {
          errors = true;
          const key = `parameters.${index}.cost`;
          Object.assign(listErrors, {
            [key]: ["The cost field is required."],
          });
        }

        if (isNaN(parseInt(element.cost))) {
          errors = true;
          const key = `parameters.${index}.cost`;
          Object.assign(listErrors, {
            [key]: ["The cost must be number."],
          });
        }
      });
      this.form.transportations.forEach((element, index) => {
        if (
          element.transportation_id == 0 ||
          element.transportation_id == null
        ) {
          errors = true;
          const key = `transportations.${index}.transportation_id`;
          Object.assign(listErrors, {
            [key]: ["The transportation field is required."],
          });
        }
        if (element.cost == null) {
          errors = true;
          const key = `transportations.${index}.cost`;
          Object.assign(listErrors, {
            [key]: ["The cost field is required."],
          });
        }
        if (isNaN(parseInt(element.cost))) {
          errors = true;
          const key = `transportations.${index}.cost`;
          Object.assign(listErrors, {
            [key]: ["The cost must be number."],
          });
        }
      });

      this.$store.state.errors = listErrors;
      return errors;
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
