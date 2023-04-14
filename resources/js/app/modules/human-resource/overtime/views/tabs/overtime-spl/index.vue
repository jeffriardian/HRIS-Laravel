<template>
  <div class="card">
    <!-- Card Body -->
    <div class="card-body">
      <!-- Card Header -->
      <div class="row mb-2">
        <div class="col-sm-4">
          <div class="search-box mr-2 mb-2 d-inline-block">
            <div class="position-relative">
              <input
                type="text"
                class="form-control"
                :placeholder="$t('search')"
                v-model="keyword"
              />
              <i class="bx bx-search-alt search-icon"></i>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="float-right">
            <a
              href="/storage/form-overtime/overtime.xlsx"
              class="btn btn-dark"
              target="_blank"
              v-b-tooltip.hover
              title="Format Format Import"
            >
              <i class="mdi mdi-cloud-download-outline"></i>
              Sample
            </a>
            <a
              href="/storage/form-overtime/SPL.xls"
              class="btn btn-danger"
              target="_blank"
              v-b-tooltip.hover
              title="Form SPL"
            >
              <i class="mdi mdi-cloud-download-outline"></i>
              Download
            </a>
            <b-dropdown class="ml-2">
              <template v-slot:button-content>
                <i class="mdi mdi-database-export"></i>
                {{ $t("export") }}
                <i class="mdi mdi-chevron-down"></i>
              </template>
              <b-dropdown-item @click="exportFile('excel')">
                <i class="mdi mdi-file-excel"></i> Excel
              </b-dropdown-item>
              <b-dropdown-item @click="exportFile('pdf')">
                <i class="mdi mdi-file-pdf"></i> PDF
              </b-dropdown-item>
            </b-dropdown>
            <b-button
              v-b-modal.form-multiple-create
              variant="primary"
              class="ml-2"
            >
              <i class="mdi mdi-cloud-upload-outline"></i> Import
            </b-button>
            <router-link
              :to="{ name: 'overtimes.add' }"
              class="btn btn-success ml-2"
            >
              <i class="mdi mdi-plus"></i>
              {{ $tc("create") }}
            </router-link>
          </div>
        </div>
      </div>
      <!-- End Card Header -->
      <b-table
        :sort-by.sync="tableParams.order_column"
        :sort-desc.sync="tableParams.order_direction"
        responsive="sm"
        thead-class="thead-light"
        small
        hover
        bordered
        :items="collection.data"
        :fields="fields"
        show-empty
      >
        <template v-slot:head()="data">{{ $tc(data.label) }}</template>
        <template v-slot:cell(index)="row">{{
          tableParams.page !== 1
            ? row.index + 1 + tableParams.per_page * (tableParams.page - 1)
            : row.index + 1
        }}</template>
        <template v-slot:cell(employee_id)="row"
          >{{ row.item.employee.nik }} - {{ row.item.employee.name }}</template
        >
        <template v-slot:cell(total_time)="row">{{
          row.item.total_time | formatTotalTime
        }}</template>
        <template v-slot:cell(approvals)="row">
          <div v-html="$options.filters.formatStatus(row.item.approval)"></div>
        </template>
        <template v-slot:cell(actions)="row">
          <a
            v-if="
              row.item.approval == null ||
              row.item.approval.approved_status != 0
            "
            href="javascript:void(0)"
            @click="openModalFormApproval(row.index, row.item.id)"
            class="text-success"
            v-b-tooltip.hover
            :title="$tc('approval')"
          >
            <i class="mdi mdi-account-edit-outline font-size-16"></i>
          </a>
          <a
            href="javascript:void(0)"
            class="text-info"
            v-b-tooltip.hover
            title="Detail"
            @click="openModalDetail(row.item.id)"
          >
            <i class="mdi mdi-eye-outline font-size-16"></i>
          </a>
          <router-link
            v-if="
              row.item.approval == null ||
              row.item.approval.approved_status != 0
            "
            :to="{
              name: 'overtimes.edit',
              params: { id: row.item.id },
            }"
            class="text-primary"
            v-b-tooltip.hover
            :title="$tc('edit')"
          >
            <i class="mdi mdi-pencil-box-outline font-size-16"></i>
          </router-link>
          <a
            href="javascript:void(0)"
            @click="remove(row.item)"
            class="text-danger"
            v-b-tooltip.hover
            :title="$tc('delete')"
          >
            <i class="mdi mdi-trash-can-outline font-size-16"></i>
          </a>
        </template>
      </b-table>
    </div>
    <!-- End Card Body -->
    <!-- Card Footer -->
    <div
      class="card-footer bg-white d-flex justify-content-between align-items-center"
    >
      <div>
        <b-form-select
          v-model="tableParams.per_page"
          :options="tableParams.pageOptions"
        ></b-form-select>
      </div>
      <div>
        <p v-if="collection.data">
          {{
            $t("table summary", {
              from: collection.meta.from,
              to: collection.meta.to,
              total: collection.meta.total,
            })
          }}
        </p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          class="v-step-4"
          :total-rows="collection.meta.total"
          :per-page="collection.meta.per_page"
          v-if="collection.data && collection.data.length > 0"
        ></b-pagination>
      </div>
    </div>
    <!-- End Card Footer -->
    <!-- Modal Form Multiple Create -->
    <b-modal
      id="form-multiple-create"
      title="Form Multiple Create"
      size="lg"
      no-close-on-backdrop
      no-close-on-esc
      :hide-header-close="loadingProcess"
    >
      <form v-on:submit.prevent="submitImport">
        <form-upload></form-upload>
      </form>
      <template v-slot:modal-footer>
        <b-button
          class="btn btn-secondary ml-2"
          @click="hideModalImport()"
          v-if="!loadingProcess"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t("cancel") }}
        </b-button>
        <button
          class="btn btn-primary"
          @click.prevent="submitImport"
          :disabled="loadingProcess"
        >
          <b-spinner v-if="loadingProcess" small class="mr-2"></b-spinner>
          <i v-if="!loadingProcess" class="fas fa-file-upload mr-2"></i>
          {{ $t("import") }}
        </button>
      </template>
    </b-modal>
    <!-- End Modal Form Multiple Create -->
    <!-- Modal Import Overtime -->
    <b-modal
      ref="temporary-modal"
      title="List Data"
      no-close-on-backdrop
      no-close-on-esc
      hide-header-close
    >
      <div class="d-block text-center">
        <b-table
          responsive="sm"
          thead-class="thead-light"
          small
          hover
          bordered
          :items="temporaryData.data"
          :fields="temporaryFields"
          show-empty
        >
          <template v-slot:head()="data">{{ $tc(data.label) }}</template>
          <template v-slot:cell(employee_id)="row"
            >{{ row.item.employee.nik }} -
            {{ row.item.employee.name }}</template
          >
        </b-table>
      </div>
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        pills
        class="v-step-4 float-right"
      ></b-pagination>
      <template v-slot:modal-footer>
        <button
          class="btn btn-danger"
          @click="cancelImport()"
          :disabled="loadingProcessCancelMigration"
        >
          <b-spinner
            v-if="loadingProcessCancelMigration"
            small
            class="mr-2"
          ></b-spinner>
          <i
            v-if="!loadingProcessCancelMigration"
            class="far fa-times-circle"
          ></i>
          {{ $t("cancel") }}
        </button>
        <button
          class="btn btn-primary"
          @click="saveImport()"
          :disabled="loadingProcessMigration"
        >
          <b-spinner
            v-if="loadingProcessMigration"
            small
            class="mr-2"
          ></b-spinner>
          <i v-if="!loadingProcessMigration" class="far fa-save mr-2"></i>
          {{ $t("save") }}
        </button>
      </template>
    </b-modal>
    <!-- End of Modal Import Overtime -->
    <!-- Modal Overtime Approval -->
    <b-modal
      ref="modal-approval"
      title="Form Approval"
      no-close-on-backdrop
      no-close-on-esc
      hide-header-close
    >
      <b-form-group label="Status">
        <b-form-radio-group
          id="btn-radios-2"
          v-model="formApproval.approved_status"
          :options="options"
          buttons
          button-variant="outline-primary"
          style="width: 100%"
          @change="approvalSelected"
        ></b-form-radio-group>
        <p class="text-danger" v-if="errors.approved_status">
          {{ errors.approved_status[0] }}
        </p>
      </b-form-group>
      <b-form-group :label="$tc('description')" v-if="!hideDesc">
        <b-form-textarea rows="5" v-model="formApproval.reject_description" />
        <p class="text-danger" v-if="errors.reject_description">
          {{ errors.reject_description[0] }}
        </p>
      </b-form-group>
      <template v-slot:modal-footer>
        <b-button
          class="btn btn-secondary ml-2"
          @click="hideModalApproval()"
          v-if="!loadingProcess"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          {{ $t("cancel") }}
        </b-button>
        <button
          class="btn btn-primary"
          @click.prevent="submitApproval"
          :disabled="loadingProcess"
        >
          <b-spinner v-if="loadingProcess" small class="mr-2"></b-spinner>
          <i v-if="!loadingProcess" class="fas fa-save mr-2"></i>
          {{ $t("save") }}
        </button>
      </template>
    </b-modal>
    <!-- End of Modal Overtime Approval -->
    <!-- Modal Detail Overtime -->
    <b-modal
      ref="modal-detail-approval"
      title="Detail Overtime"
      size="lg"
      no-close-on-backdrop
      no-close-on-esc
    >
      <div class="row">
        <div class="col-12">
          <div class="d-flex">
            <h4 class="mr-2">Status :</h4>
            <div
              class="mt-1"
              v-html="$options.filters.formatStatus(detail.approval)"
            ></div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-4">
          <img
            :src="'/storage/human-resources/overtime/' + detail.attachment"
            alt
            class="img-fluid"
          />
        </div>
        <div class="col-8">
          <table class="table table-bordered table-sm table-hover">
            <tr>
              <td>
                <b>Name</b>
              </td>
              <td>{{ (detail.employee || {}).name }}</td>
            </tr>
            <tr>
              <td>
                <b>Nik</b>
              </td>
              <td>{{ (detail.employee || {}).nik }}</td>
            </tr>
            <tr>
              <td>
                <b>Shift</b>
              </td>
              <td>{{ detail.shift | formatShift }}</td>
            </tr>
            <tr>
              <td>
                <b>Scan In - Scan Out</b>
              </td>
              <td>
                {{ (detail.shift || {}).schedule_in }} -
                {{ (detail.shift || {}).schedule_out }}
              </td>
            </tr>
            <tr>
              <td>
                <b>Request Date</b>
              </td>
              <td>{{ detail.request_date }}</td>
            </tr>
            <tr>
              <td>
                <b>Overtime Request</b>
              </td>
              <td>{{ detail.total_time | formatTotalTime }}</td>
            </tr>
            <tr>
              <td>
                <b>Day Off</b>
              </td>
              <td>{{ detail.is_holiday | formatBoolean }}</td>
            </tr>
            <tr>
              <td>
                <b>Without Reducing Rest Hours</b>
              </td>
              <td>{{ detail.without_reducing_rest_hours | formatBoolean }}</td>
            </tr>
            <tr>
              <td>
                <b>Saturday</b>
              </td>
              <td>{{ detail.is_saturday | formatBoolean }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12">
          <b-tabs content-class="mt-3">
            <b-tab active>
              <template v-slot:title
                ><i class="fas fa-money-bill-alt"></i> Rates
              </template>
              <div class="card m-b-50">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col">
                      <h5 class="card-title">Overtime Rates</h5>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm table-hover">
                    <thead>
                      <tr>
                        <th class="text-center align-middle">L1</th>
                        <th class="text-center align-middle">L2</th>
                        <th class="text-center align-middle">L3</th>
                        <th class="text-center align-middle">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-center" style="min-width: 180px">
                          {{ (detail.overtime_rates || {}).L1 | formatHour }} -
                          {{
                            (detail.overtime_rates || {}).rates_L1
                              | formatRupiah
                          }}
                        </td>
                        <td class="text-center" style="min-width: 180px">
                          {{ (detail.overtime_rates || {}).L2 | formatHour }} -
                          {{
                            (detail.overtime_rates || {}).rates_L2
                              | formatRupiah
                          }}
                        </td>
                        <td class="text-center" style="min-width: 180px">
                          {{ (detail.overtime_rates || {}).L3 | formatHour }} -
                          {{
                            (detail.overtime_rates || {}).rates_L3
                              | formatRupiah
                          }}
                        </td>
                        <td class="text-center">
                          {{
                            (detail.overtime_rates || {}).total_rates
                              | formatRupiah
                          }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </b-tab>
            <b-tab title="History">
              <template v-slot:title
                ><i class="fas fa-history"></i> History
              </template>
              <div class="card m-b-50">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col">
                      <h5 class="card-title">History</h5>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm table-hover">
                    <thead>
                      <tr>
                        <th
                          class="text-center align-middle"
                          style="width: 120px"
                        >
                          Overtime Before Duration
                        </th>
                        <th
                          class="text-center align-middle"
                          style="width: 120px"
                        >
                          Overtime After Duration
                        </th>
                        <th
                          class="text-center align-middle"
                          style="width: 120px"
                        >
                          Total Time
                        </th>
                        <th
                          class="text-center align-middle"
                          style="width: 120px"
                        >
                          Approved Status
                        </th>
                        <th class="text-center align-middle">
                          Reject Description
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in detail.histories" :key="index">
                        <td class="text-center">
                          {{ row.overtime_before_duration }}
                        </td>
                        <td class="text-center">
                          {{ row.overtime_after_duration }}
                        </td>
                        <td class="text-center">
                          {{ row.total_time | formatTotalTime }}
                        </td>
                        <td class="text-center">
                          <div
                            v-html="
                              $options.filters.formatStatus(row.approved_status)
                            "
                          ></div>
                        </td>
                        <td>{{ row.reject_description }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </b-tab>
          </b-tabs>
        </div>
        <div class="col"></div>
      </div>
      <template v-slot:modal-footer>
        <b-button variant="secondary" @click="closeModalDetail()"
          >Close</b-button
        >
      </template>
    </b-modal>
    <!-- End of Modal Detail Overtime -->
  </div>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
import formUpload from "./form-upload";
export default {
  components: {
    formUpload,
  },
  data() {
    return {
      fields: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align: center; width: 35px;",
          tdClass: "text-center",
        },
        {
          key: "employee_id",
          label: "employee",
          thStyle: "text-align: center;",
          sortable: true,
        },
        {
          key: "request_date",
          label: "request date",
          thStyle: "text-align: center;",
          tdClass: "text-center",
          sortable: true,
        },
        {
          key: "overtime_before_duration",
          label: "overtime before duration",
          thStyle: "text-align: center;",
          tdClass: "text-center",
          sortable: true,
        },
        {
          key: "overtime_after_duration",
          label: "overtime after duration",
          thStyle: "text-align: center;",
          tdClass: "text-center",
          sortable: true,
        },
        {
          key: "total_time",
          label: "total time",
          thStyle: "text-align: center;",
          tdClass: "text-center",
          sortable: true,
        },
        {
          key: "approvals",
          label: "status",
          thStyle: "text-align: center;",
          sortable: true,
        },
        {
          key: "actions",
          label: "action",
          thStyle: "text-align: center; width: 100px;",
          tdClass: "text-center",
        },
      ],
      keyword: "",
      loadingProcess: false,
      temporaryFields: [{ key: "employee_id", label: "name", sortable: true }],
      rows: 0,
      perPage: 8,
      currentPage: 1,
      loadingProcessMigration: false,
      loadingProcessCancelMigration: false,
      options: [
        { text: "Approve", value: 1 },
        { text: "Reject", value: 0 },
      ],
      hideDesc: true,
    };
  },
  computed: {
    ...mapState(["errors"]),
    ...mapState("overtime", {
      collection: (state) => state.collection,
      formApproval: (state) => state.formApproval,
      formUpload: (state) => state.formUpload,
      temporaryData: (state) => state.dataTemporary,
      detail: (state) => state.detail,
    }),
    ...mapState("notification", {
      notify: (state) => state.notification,
    }),
    tableParams: {
      get() {
        //MENGAMBIL VALUE PAGE DARI VUEX MODULE
        return this.$store.state.humanResource.overtime.tableParams;
      },
      set(params) {
        this.$store.commit("humanResource/overtime/SET_TABLE_PARAMS", params);
      },
    },
  },
  watch: {
    tableParams: {
      handler: function (params) {
        let requestParams = {
          keyword: this.keyword,
          page: params.page,
          per_page: params.per_page,
          order_column: params.order_column,
          order_direction: params.order_direction,
        };
        this.load(requestParams);
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
      this.load(requestParams);
    },
    notify() {
      this.load(this.tableParams);
    },
    errors() {
      if (typeof this.errors == "object") {
        if (Object.keys(this.errors).length > 0) {
          this.loadingProcess = false;
        }
      }
    },
  },
  created() {
    this.load(this.tableParams);
  },
  methods: {
    //MENGAMBIL FUNGSI DARI VUEX MODULE
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapActions("overtime", [
      "load",
      "loadDetail",
      "destroy",
      "importExcelToTemporary",
      "migrationFromTemporary",
      "cancelStoreImport",
      "storeOrUpdateApproval",
    ]),
    ...mapMutations("overtime", ["CLEAR_FORM"]),
    approvalSelected(value) {
      this.hideDesc = value == 0 ? false : true;
    },
    totalTime(startTime, endTime) {
      const start = startTime.split(":");
      const end = endTime.split(":");

      const startTimeHour = parseInt(start[0]);
      const startTimeMinutes = parseInt(start[1]);

      const endTimeHour = parseInt(end[0]);
      const endTimeMinutes = parseInt(end[1]);

      const totalHour =
        endTimeHour < startTimeHour
          ? 24 - startTimeHour + endTimeHour
          : endTimeHour - startTimeHour;

      const totalMinutes = startTimeMinutes + endTimeMinutes;

      const result =
        totalMinutes == 0
          ? `${totalHour} jam`
          : `${totalHour}.${totalMinutes} jam`;
      return result;
    },
    remove(item) {
      this.$confirm(
        this.$t("delete confirmation", {
          item: item.name,
          module: this.$tc("overtime"),
        }),
        this.$t("warning"),
        {
          confirmButtonText: this.$t("ok"),
          cancelButtonText: this.$t("cancel"),
          type: "warning",
        }
      )
        .then(() => {
          this.destroy(item.id);
          this.$message({
            type: "success",
            message: this.$t("delete successfully"),
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: this.$t("delete canceled"),
          });
        });
    },
    exportFile(type) {
      window.open(
        `/api/overtime-export-${type}?api_token=${this.$store.state.token}`
      );
    },
    hideModalImport() {
      this.$bvModal.hide("form-multiple-create");
    },
    submitImport() {
      this.loadingProcess = true;
      let form = new FormData();
      for (const key in this.formUpload) {
        if (this.formUpload[key] != null) {
          form.append(key, this.formUpload[key]);
        }
      }
      this.importExcelToTemporary(form).then(() => {
        this.$refs["temporary-modal"].show();
        this.loadingProcess = false;
      });
    },
    saveImport() {
      this.loadingProcessMigration = true;
      this.migrationFromTemporary().then(() => {
        this.$refs["temporary-modal"].hide();
        this.hideModalImport();
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("overtime"),
            message: this.$tc("add", 2),
          }),
        });
        this.load(this.tableParams);
        this.loadingProcessMigration = false;
      });
    },
    cancelImport() {
      this.loadingProcessCancelMigration = true;
      this.cancelStoreImport().then(() => {
        this.$refs["temporary-modal"].hide();
        this.hideModalImport();
        this.$message({
          type: "danger",
          message: this.$t("present perfect", {
            object: this.$tc("overtime"),
            message: this.$tc("cancel", 2),
          }),
        });
        this.loadingProcessCancelMigration = false;
      });
    },
    openModalFormApproval(index, id) {
      this.formApproval.overtime_id = id;
      if (this.collection.data[index].approval != null) {
        this.formApproval.approved_status = this.collection.data[
          index
        ].approval.approved_status;

        this.formApproval.reject_description = this.collection.data[
          index
        ].approval.reject_description;

        this.approvalSelected(this.formApproval.approved_status);
      }

      this.$refs["modal-approval"].show();
    },
    hideModalApproval() {
      this.$refs["modal-approval"].hide();
      this.CLEAR_FORM();
      this.CLEAR_ERRORS();
      this.hideDesc = true;
    },
    submitApproval() {
      this.loadingProcess = true;
      if (this.formApproval.approved_status == 1)
        this.formApproval.reject_description = "";
      this.storeOrUpdateApproval().then(() => {
        this.hideModalApproval();
        this.$message({
          type: "success",
          message: this.$t("present perfect", {
            object: this.$tc("overtime"),
            message: this.$tc("approved", 2),
          }),
        });
      });
      this.loadingProcess = false;
    },
    openModalDetail(id) {
      this.loadDetail(id);
      this.$refs["modal-detail-approval"].show();
    },
    closeModalDetail() {
      this.$refs["modal-detail-approval"].hide();
      this.CLEAR_FORM();
    },
  },
  filters: {
    formatTotalTime(value) {
      if (value != undefined) {
        if (value.toString().includes(".")) {
          const hour = value.toString().split(".")[0];
          const minutes = value.toString().split(".")[1];
          if (hour == 0) {
            if (minutes.length == 2) {
              return `${minutes} menit`;
            } else {
              return `${minutes}0 menit`;
            }
          } else {
            if (minutes == 0) {
              return `${hour} jam`;
            } else {
              if (minutes.length == 2) {
                return `${hour} jam ${minutes} menit`;
              } else {
                return `${hour} jam ${minutes}0 menit`;
              }
            }
          }
        } else {
          return `${value} jam`;
        }
      }
      // return value.toString().includes(".") ? `${value}0 jam` : `${value} jam`;
    },
    formatStatus(value) {
      if (value == null) {
        return `<span class="badge badge-pill badge-soft-info font-size-12">Waiting for Approval</span>`;
      } else {
        const approved = `<span class="badge badge-pill badge-soft-success font-size-12">Approved</span>`;
        const reject = `<span class="badge badge-pill badge-soft-danger font-size-12">Reject</span`;
        if (
          (typeof value == "object" && value.approved_status == 1) ||
          value == 1
        ) {
          return approved;
        }

        if (
          (typeof value == "object" && value.approved_status == 1) ||
          value == 0
        ) {
        }
        {
          return reject;
        }
      }
    },
    formatShift(value) {
      return (value.shift || {}).name;
    },
    formatHour(value) {
      if (value != undefined) {
        if (value != 0) {
          const split = value.toString().split(".");
          const hour = split[0];
          if (split.length > 1) {
            const minutes = Math.round(`0.${split[1]}` * 60);
            if (hour == 0) {
              return minutes + " menit ";
            } else {
              return hour + " jam " + minutes + " menit ";
            }
          } else {
            if (hour == 0) {
              return "";
            } else {
              return hour + " jam ";
            }
          }
        } else {
          return;
        }
      }
    },
    formatRupiah(value) {
      if (value != undefined) {
        if (value != 0) {
          let number_string = value.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

          if (ribuan) {
            const separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
          }
          return "Rp. " + rupiah;
        }
      }
    },
    formatBoolean(value) {
      if (value != undefined) return value == 1 ? "Yes" : "No";
    },
  },
};
</script>
