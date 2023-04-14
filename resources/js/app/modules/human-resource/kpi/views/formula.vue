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
        </div>
      </div>
    </div>
    <b-table
      :items="formulas.data"
      :fields="fields"
      :sort-by.sync="tableParams.order_column"
      :sort-desc.sync="tableParams.order_direction"
      small
      hover
      bordered
      responsive="sm"
      thead-class="thead-light"
    >
      <template v-slot:head()="data">{{ $t(data.label) }}</template>
      <template
        v-slot:cell(index)="row"
      >{{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}</template>
      <template
        v-slot:cell(unit_of_measurement)="row"
      >{{ row.item.unit_of_measurement | capitalize }}</template>
      <template v-slot:cell(actions)="row">
        <a
          href="javascript:void(0)"
          @click="showModal(row.item)"
          class="text-primary"
          v-b-tooltip.hover
          :title="$tc('edit')"
        >
          <i class="mdi mdi-pencil-box-outline font-size-16"></i>
        </a>
      </template>
    </b-table>
    <div class="d-flex justify-content-between">
      <div>
        <b-form-select v-model="tableParams.per_page" :options="tableParams.pageOptions"></b-form-select>
      </div>
      <div>
        <p
          v-if="formulas.data"
        >{{ $t('table summary', { from: formulas.meta.from, to: formulas.meta.to, total: formulas.meta.total}) }}</p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          :total-rows="formulas.meta.total"
          :per-page="formulas.meta.per_page"
          v-if="formulas.data && formulas.data.length > 0"
        ></b-pagination>
      </div>
    </div>

    <b-modal ref="modal-edit-formula" size="lg" title="Edit Formula" no-close-on-backdrop>
      <div class="form-group">
        <label for>{{ $t('key performance indicators') }} :</label>
        <input
          type="text"
          class="form-control"
          :class="{ 'border-danger': errors.assessment_point }"
          v-model="formEditFormula.assessment_point"
        />
        <p class="text-danger" v-if="errors.assessment_point">{{ errors.assessment_point[0] }}</p>
      </div>
      <div class="form-group">
        <label for>{{ $t('target') }} :</label>
        <input
          type="text"
          class="form-control"
          :class="{ 'border-danger': errors.target }"
          v-model="formEditFormula.target"
        />
        <p class="text-danger" v-if="errors.target">{{ errors.target[0] }}</p>
      </div>
      <div class="form-group">
        <label for>{{ $t('numerator') }} :</label>
        <input
          type="text"
          class="form-control"
          :class="{ 'border-danger': errors.numerator }"
          v-model="formEditFormula.numerator"
        />
        <p class="text-danger" v-if="errors.numerator">{{ errors.numerator[0] }}</p>
      </div>
      <div class="form-group">
        <label for>{{ $t('denominator') }} :</label>
        <input
          type="text"
          class="form-control"
          :class="{ 'border-danger': errors.denominator }"
          v-model="formEditFormula.denominator"
        />
        <p class="text-danger" v-if="errors.denominator">{{ errors.denominator[0] }}</p>
      </div>
      <b-row>
        <b-col>
          <div class="form-group">
            <label for>{{ $t('assessment category') }} :</label>
            <b-form-select
              v-model="formEditFormula.assessment_category"
              :options="assessmentCategoryOptions"
            ></b-form-select>
          </div>
        </b-col>
        <b-col>
          <div class="form-group">
            <label for>{{ $t('target point') }} :</label>
            <div class="input-group mb-3">
              <input
                type="text"
                class="form-control"
                :class="{ 'border-danger': errors.target_point }"
                v-model="formEditFormula.target_point"
                aria-describedby="basic-addon2"
              />
              <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">%</span>
              </div>
            </div>
            <p class="text-danger" v-if="errors.target_point">{{ errors.target_point[0] }}</p>
          </div>
        </b-col>
        <b-col>
          <div class="form-group">
            <label for>{{ $t('unit of measurement') }} :</label>
            <!-- <input
              type="text"
              class="form-control"
              :class="{ 'border-danger': errors.unit_of_measurement }"
              v-model="formEditFormula.unit_of_measurement"
            />-->
            <b-form-select
              v-model="formEditFormula.unit_of_measurement"
              :options="unitOfMeasurementOptions"
            ></b-form-select>
            <p
              class="text-danger"
              v-if="errors.unit_of_measurement"
            >{{ errors.unit_of_measurement[0] }}</p>
          </div>
        </b-col>
      </b-row>
      <div class="row bg-light-gray">
        <div class="col-md-12 text-left">
          <b>Grade</b>
        </div>
        <div class="col-md-12 table-responsive-lg mt-2 mb-0">
          <table class="table table-md table-bordered">
            <thead>
              <tr>
                <th scope="col" width="5%">#</th>
                <th scope="col" width="0%" class="text-left">Skor Minimum</th>
                <th scope="col" width="30%" class="text-left">Skor Maximum</th>
                <th scope="col" width="30%" class="text-left">Nilai</th>
                <th scope="col" width="5%">*</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in formEditFormula.kpi_grades" :key="index">
                <th scope="row">{{ index+1 }}</th>
                <td class="text-left">
                  <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="item.score_min"
                    :class="{ 'border-danger': errors[`kpi_grades.${index}.score_min`] }"
                    placeholder="Minimun Skor"
                  />
                  <p
                    class="text-danger"
                    v-if="errors[`kpi_grades.${index}.score_min`]"
                  >{{ errors[`kpi_grades.${index}.score_min`][0] }}</p>
                </td>
                <td class="text-right">
                  <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="item.score_max"
                    :class="{ 'border-danger': errors[`kpi_grades.${index}.score_max`] }"
                    placeholder="Maximum Skor"
                  />
                  <p
                    class="text-danger"
                    v-if="errors[`kpi_grades.${index}.score_max`]"
                  >{{ errors[`kpi_grades.${index}.score_max`][0] }}</p>
                </td>
                <td class="text-left">
                  <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="item.grade"
                    :class="{ 'border-danger': errors[`kpi_grades.${index}.grade`] }"
                  />
                  <p
                    class="text-danger"
                    v-if="errors[`kpi_grades.${index}.grade`]"
                  >{{ errors[`kpi_grades.${index}.grade`][0] }}</p>
                </td>
                <td class="align-middle">
                  <!-- Delete Button -->
                  <a
                    href="#"
                    class="action-button text-danger"
                    data-toggle="tooltip"
                    title="Batal"
                    @click.prevent="deleteKPIGrade(index)"
                  >
                    <i class="far fa-times-circle"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="text-right mt-2">
            <span class="btn btn-sm btn-success" @click="addKPIGrade">
              <i class="fa fa-plus"></i> Tambah
            </span>
          </div>
        </div>
      </div>
      <!-- {{ formEditFormula }} -->
      <template v-slot:modal-footer>
        <b-button class="btn btn-secondary ml-2" @click="hideModal()">
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
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
export default {
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
          key: "target_point",
          label: "target point",
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
          key: "actions",
          label: "action",
          thStyle: "text-align: center; width: 80px;",
          tdClass: "text-center",
        },
      ],
      assessmentCategoryOptions: [
        { value: "percentage", text: "Percentage" },
        { value: "deduction", text: "Deduction" },
        { value: "bonus point", text: "Bonus Point" },
      ],
      unitOfMeasurementOptions: [
        { value: "jumlah", text: "Jumlah" },
        { value: "nilai", text: "Nilai" },
      ],
      formEditFormula: {},
      isLoadingProcess: false,
    };
  },
  computed: {
    ...mapState("kpi", {
      formulas: (state) => state.formulas,
    }),
    tableParams: {
      get() {
        return this.$store.state.humanResource.kpi.tableParams;
      },
      set(params) {
        this.$store.commit("humanResource/kpi/SET_TABLE_PARAMS", params);
      },
    },
    ...mapState(["errors"]),
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
        this.loadKpiFormulas(requestParams);
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
      this.loadKpiFormulas(requestParams);
    },
    errors() {
      if (typeof this.errors === "object") {
        this.isLoadingProcess = false;
      }
    },
  },
  methods: {
    ...mapActions("kpi", ["loadKpiFormulas", "updateKpiFormula"]),
    ...mapMutations(["CLEAR_ERRORS"]),
    showModal(item) {
      this.formEditFormula = { ...item };
      this.$refs["modal-edit-formula"].show();
    },
    hideModal() {
      this.$refs["modal-edit-formula"].hide();
      this.formEditFormula = {};
      this.isLoadingProcess = false;
      this.CLEAR_ERRORS();
    },
    addKPIGrade() {
      this.formEditFormula.kpi_grades.push({
        id: null,
        score_min: null,
        score_max: null,
        grade: null,
      });
    },
    deleteKPIGrade(index) {
      this.formEditFormula.kpi_grades.splice(index, 1);
    },
    submit() {
      this.isLoadingProcess = true;
      this.updateKpiFormula(this.formEditFormula).then(() => {
        this.hideModal();
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("formula"),
            message: this.$tc("update", 2),
          }),
        });
        this.CLEAR_ERRORS();
      });
    },
  },
  filters: {
    capitalize(value) {
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>