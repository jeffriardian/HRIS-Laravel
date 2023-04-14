<template>
  <div>
    <div class="row">
      <div class="col-sm-4">
        <div class="search-box mr-2 mb-2 d-inline-block">
          <div class="position-relative">
            <input type="text" class="form-control" :placeholder="$t('search')" v-model="keyword" />
            <i class="bx bx-search-alt search-icon"></i>
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="float-right">
          <b-dropdown right>
            <template v-slot:button-content>
              {{ $t('export') }}
              <i class="mdi mdi-chevron-down"></i>
            </template>
            <b-dropdown-item>
              <i class="mdi mdi-file-excel"></i> Excel
            </b-dropdown-item>
            <b-dropdown-item>
              <i class="mdi mdi-file-pdf"></i> PDF
            </b-dropdown-item>
          </b-dropdown>
          <b-button v-b-modal="'form-modal'" class="btn btn-success">
            <i class="mdi mdi-plus mr-1"></i>
            {{ $tc('create') }}
          </b-button>
        </div>
      </div>
    </div>

    <!-- Table Kpi Employee Detail -->
    <b-table :items="employees.data" :fields="fields">
      <template v-slot:head()="data">{{ $t(data.label) }}</template>
      <template
        v-slot:cell(index)="row"
      >{{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}</template>
    </b-table>
    <div class="d-flex justify-content-between">
      <div>
        <b-form-select v-model="tableParams.per_page" :options="tableParams.pageOptions"></b-form-select>
      </div>
      <div>
        <p
          v-if="employees.data"
        >{{ $t('table summary', { from: employees.meta.from, to: employees.meta.to, total: employees.meta.total}) }}</p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          :total-rows="employees.meta.total"
          :per-page="employees.meta.per_page"
          v-if="employees.data && employees.data.length > 0"
        ></b-pagination>
      </div>
    </div>

    <!-- Modal Tambah Kpi -->
    <b-modal id="form-modal" size="xl" ref="modal" title="Tambah Kpi Karyawan" no-close-on-backdrop>
      <div class="row">
        <div class="col-3">
          <div class="form-group">
            <label for="exampleInputEmail1">{{ $tc('employee', 0) }} :</label>
            <v-select
              v-model="form.employeeId"
              :options="collectionEmployee.data"
              label="name"
              :reduce="name => name.id"
            ></v-select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" @click="showModalFormulaOption()">Pilih Formula</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="alert alert-danger text-center" role="alert" v-if="form.items.length == 0">
            <b>Silahkan pilih formula terlebih dahulu..!!!</b>
          </div>
          <div v-if="form.items.length > 0">
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th scope="col" width="2%">#</th>
                  <th scope="col" width="28%" class="text-left">Poin Penilaian</th>
                  <th scope="col" width="28%" class="text-left">Target Penilaian</th>
                  <th scope="col" width="13%" class="text-left">Persentase</th>
                  <th scope="col" width="3%">*</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in form.items" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.assessment_point }}</td>
                  <td>{{ item.target }}</td>
                  <td>
                    <div class="input-group input-group-sm">
                      <input
                        type="text"
                        class="form-control"
                        v-model="form.items[index].percen"
                        aria-describedby="basic-addon2"
                      />
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">%</span>
                      </div>
                    </div>
                    <!-- <p class="text-danger" v-if="errors.target_point">{{ errors.target_point[0] }}</p> -->
                  </td>
                  <td>
                    <a
                      href="#"
                      class="action-button text-secondary"
                      data-toggle="tooltip"
                      title="Batal"
                      @click.prevent="deleteKpiFormula(index)"
                    >
                      <i class="mdi mdi-trash-can-outline font-size-16"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <template v-slot:modal-footer>
        <b-button class="btn btn-secondary ml-2" @click="hideModal('create-kpi')">
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t('cancel') }}
        </b-button>
        <button class="btn btn-primary" @click.prevent="submit" :disabled="isLoadingProcess">
          <b-spinner v-if="isLoadingProcess" small class="mr-2"></b-spinner>
          <i v-else class="far fa-save mr-2"></i>
          {{ $t('save') }}
        </button>
      </template>
    </b-modal>

    <!-- Modal List Formula -->
    <b-modal
      id="form-modal-formula-option"
      size="xl"
      ref="modal"
      title="KPI Formula"
      no-close-on-backdrop
    >
      <b-table :items="formulas.data" :fields="fieldFormulas" outlined>
        <template v-slot:head()="data">{{ $t(data.label) }}</template>
        <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>
        <template v-slot:cell(pilih)="row">
          <b-form-checkbox value="1" unchecked-value="0" @input="addKpiFormula(row, $event)"></b-form-checkbox>
        </template>
      </b-table>
      <template v-slot:modal-footer>
        <button class="btn btn-primary" @click="hideModal('kpi-formula')">Ok</button>
      </template>
    </b-modal>
  </div>
</template>
<script>
import vSelect from "vue-select";
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      keyword: "",
      fields: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align: center; width: 35px;",
          tdClass: "text-center",
        },
        {
          key: "employee_id",
          label: "name",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
      ],
      fieldFormulas: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align: center; width: 35px;",
          tdClass: "text-center",
        },
        {
          key: "assessment_point",
          label: "key performance indicators",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "unit_of_measurement",
          label: "unit of measurement",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "target",
          label: "target",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "numerator",
          label: "numerator",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "denominator",
          label: "denominator",
          sortable: true,
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "pilih",
          label: "choose",
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
      ],
      kpiFormulaOptions: [],
      form: {
        employeeId: "",
        items: [],
      },
      // optionAssessmentCategory: [
      //   {
      //     value: "presentase",
      //     text: "Presentase",
      //   },
      //   {
      //     value: "deduction",
      //     text: "Deduction",
      //   },
      //   {
      //     value: "bonus point",
      //     text: "Bonus Point",
      //   },
      // ],
      isLoadingProcess: false,
    };
  },
  computed: {
    ...mapState("kpi", {
      employees: (state) => state.employees,
    }),
    ...mapState("kpi", {
      formulas: (state) => state.formulas,
    }),
    ...mapState("employee", {
      collectionEmployee: (state) => state.collectionList,
    }),
    tableParams: {
      get() {
        return this.$store.state.humanResource.kpi.tableParams;
      },
      set(params) {
        this.$store.commit("humanResource/kpi/SET_TABLE_PARAMS", params);
      },
      // selectedFormula() {
      //   return "1";
      // },
    },
  },
  watch: {
    tableParams: {
      handler(params) {
        let requestParams = {
          keyword: this.keyword,
          page: params.page,
          per_page: params.per_page,
          order_column: params.order_column,
          order_direction: params.order_direction,
        };
        this.loadKpiEmployees(requestParams);
      },
      deep: true,
    },
    keyword() {
      let requestParams = {
        keyword: this.keyword,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction,
      };
      this.loadKpiEmployees(requestParams);
    },
  },
  methods: {
    ...mapActions("kpi", ["loadKpiEmployees", "loadKpiFormulaOptions"]),
    hideModal(type) {
      if (type == "create-kpi") {
        this.form.employeeId = "";
        this.form.items = [];
        this.$bvModal.hide("form-modal");
      } else {
        this.$bvModal.hide("form-modal-formula-option");
      }
    },
    showModalFormulaOption() {
      this.loadKpiFormulaOptions();
      this.$bvModal.show("form-modal-formula-option");
    },
    addKpiFormula(item, event) {
      const index = this.form.items.findIndex(
        (obj) => obj.kpi_formula_id == item.item.id
      );
      if (event == 1) {
        this.form.items.push({
          id: null,
          kpi_formula_id: item.item.id,
          assessment_point: item.item.assessment_point,
          target: item.item.target,
          assessment_category: "presentase",
          target_score: 0,
          percen: null,
        });
      } else {
        if (index !== -1) {
          this.form.items.splice(index, 1);
        }
      }
    },
    deleteKpiFormula(index) {
      this.form.items.splice(index, 1);
    },
    submit() {
      alert("ok");
    },
  },
};
</script>