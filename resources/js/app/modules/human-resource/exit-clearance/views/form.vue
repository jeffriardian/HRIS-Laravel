<template>
  <div>
    <div class="form-group" :class="{ 'has-error': errors.employee_id }">
      <label for>{{ $tc('employee name') }}</label>
      <v-select
        @input="checkExitClearance"
        v-model="form.employee_id"
        :options="employees.data"
        label="name"
        :reduce="name => name.id"
        :class="{ 'border-danger': errors.employee_id }"
      ></v-select>
      <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.employee_id }">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-12">
            <h5 class="card-title">{{ $tc('parameter check',2) }}</h5>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
          <thead>
            <tr>
              <th>{{ $t('parameter check') }}</th>
              <th>{{ $t('status') }}</th>
              <th>{{ $t('information') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(result , index) in resultCheckExitClearance" :key="index">
              <td>{{ result.parameter }}</td>
              <td>
                <span
                  class="badge badge-pill badge-soft-success font-size-12"
                  v-if="result.status == 1"
                >Completed</span>
                <span class="badge badge-pill badge-soft-danger font-size-12" v-else>Uncompleted</span>
              </td>
              <td>
                <span
                  v-if="index == 0 && result.information != 0"
                >Rp {{ Intl.NumberFormat().format(result.information) }}</span>
                <ol v-if="index == 1 && result.information.length > 0">
                  <li
                    v-for="(information, i) in result.information"
                    :key="i"
                  >{{ information.inventory.inventory_category.name }} {{ information.inventory.name }} - {{ information.inventory.type }} ({{ information.inventory.use_stock }} item)</li>
                </ol>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  props: ["modelId"],
  data() {
    return {
      total: 0,
    };
  },
  created() {
    this.loadEmployees();
  },
  computed: {
    ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
    ...mapState("exitClearance", {
      form: (state) => state.form, //MENGAMBIL STATE DATA
    }),
    ...mapState("exitClearance", {
      resultCheckExitClearance: (state) => state.resultCheckExitClearance, //MENGAMBIL STATE DATA
    }),
    ...mapState("employee", {
      employees: (state) => state.collectionList,
    }),
  },
  methods: {
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations("exitClearance", ["CLEAR_FORM", "CLEAR_RESULT_CHECK"]), //PANGGIL MUTATIONS CLEAR_FORM
    ...mapActions("employee", { loadEmployees: "loadList" }),
    //MENGAMBIL FUNGSI DARI VUEX MODULE
    ...mapActions("exitClearance", ["send"]),
    checkExitClearance(event) {
      this.$emit("check-exit-clearance", 0);
      this.$emit("process-checking", true);
      if (this.form.employee_id == null) {
        this.CLEAR_RESULT_CHECK();
        this.$emit("check-exit-clearance", 0);
        this.$emit("process-checking", false);
      } else {
        this.send().then(() => {
          let sum = 0;
          for (const key in this.resultCheckExitClearance) {
            sum += this.resultCheckExitClearance[key].status;
          }
          this.total = sum;
          this.$emit("check-exit-clearance", sum);
          this.$emit("process-checking", false);
        });
      }
    },
  },
  //KETIKA PAGE INI DITINGGALKAN MAKA
  destroyed() {
    //FORM DI BERSIHKAN
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
    this.CLEAR_RESULT_CHECK();
  },
  components: {
    "v-select": vSelect,
  },
};
</script>
<style scoped>
ol {
  margin: 10px;
  padding: 0;
}
</style>
