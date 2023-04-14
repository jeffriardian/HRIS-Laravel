<template>
  <div class="card">
    <div class="card-body">
      <div class="row mb-2">
        <div class="col-sm-8 d-flex">
          <div class="search-box mr-2 mb-2 d-inline-block">
            <div class="position-relative">
              <input type="text" class="form-control" :placeholder="$t('search')" v-model="keyword" />
              <i class="bx bx-search-alt search-icon"></i>
            </div>
          </div>
          <select class="form-control col-md-2 mr-2" v-model="selectedFilterOffice">
            <option value>Office</option>
            <option value="ATM">ATM</option>
            <option value="SMM">SMM</option>
          </select>
          <select class="form-control col-md-2 mr-2" v-model="selectedFilterMonth">
            <option value>Mounth</option>
            <option v-for="(month, index) in 12" :key="index">{{ month }}</option>
          </select>
          <select class="form-control col-md-2" v-model="selectedFilterYear">
            <option value>Year</option>
            <option v-for="(year, index) in years.slice().reverse()" :key="index">{{ year }}</option>
          </select>
        </div>
        <div class="col-sm-4">
          <div class="float-right">
            <b-dropdown>
              <template v-slot:button-content>{{ $t('export') }}</template>
              <b-dropdown-item @click="exportFile('excel')">
                <i class="mdi mdi-file-excel"></i> Excel
              </b-dropdown-item>
              <b-dropdown-item @click="exportFile('pdf')">
                <i class="mdi mdi-file-pdf"></i> PDF
              </b-dropdown-item>
            </b-dropdown>
            <div class="file btn btn-success ml-3" :class="{disabled : isDisabled}">
              {{ text }}
              <input
                type="file"
                name="file"
                accept=".xls, .xlsx"
                @change="onFileChange($event)"
                :class="{ 'border-danger': errors.extension }"
                :disabled="isDisabled"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p class="text-danger float-right" v-if="errors.extension">{{ errors.extension[0] }}</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <b-table
            responsive
            thead-class="thead-light"
            small
            hover
            bordered
            :items="collections.data"
            :fields="fields"
          >
            <template v-slot:head()="data">{{ $t(data.label) }}</template>
            <template
              v-slot:cell(index)="row"
            >{{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}</template>
          </b-table>
        </div>
      </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
      <div>
        <b-form-select v-model="tableParams.per_page" :options="tableParams.pageOptions"></b-form-select>
      </div>
      <div>
        <p
          v-if="collections.data"
        >{{ $t('table summary', { from: collections.meta.from, to: collections.meta.to, total: collections.meta.total}) }}</p>
      </div>
      <div>
        <b-pagination
          v-model="tableParams.page"
          pills
          :total-rows="collections.meta.total"
          :per-page="collections.meta.per_page"
          v-if="collections.data && collections.data.length > 0"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from "vuex";
import vSelect from "vue-select";
export default {
  components: {
    "v-select": vSelect
  },
  data() {
    return {
      keyword: "",
      selectedFilterOffice: "",
      selectedFilterMonth: "",
      selectedFilterYear: "",
      fields: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align:center; width: 35px;",
          class: "text-center align-middle"
        },
        {
          key: "card_number",
          label: "NO Kartu",
          sortable: true,
          class: "text-center align-middle"
        },
        {
          key: "jkk",
          label: "JKK",
          class: "text-center align-middle"
        },
        {
          key: "jkm",
          label: "JKM",
          class: "text-center align-middle"
        },
        {
          key: "pk_tk",
          label: "Pemberi Kerja (TK)",
          class: "text-center align-middle"
        },
        {
          key: "tk_tk",
          label: "Tenaga Kerja (TK)",
          class: "text-center align-middle"
        },
        {
          key: "pk_jp",
          label: "Pemberi Kerja (JP)",
          class: "text-center align-middle"
        },
        {
          key: "tk_jp",
          label: "Tenaga Kerja (JP)",
          class: "text-center align-middle"
        },
        {
          key: "tk_date",
          label: "Tanggal Potong TK",
          class: "text-center align-middle"
        },
        {
          key: "jp_date",
          label: "Tanggal Potong JP",
          class: "text-center align-middle"
        },
        {
          key: "month",
          label: "Bulan",
          sortable: true,
          class: "text-center align-middle"
        },
        {
          key: "year",
          label: "Tahun",
          sortable: true,
          class: "text-center align-middle"
        },
        {
          key: "office",
          label: "Kantor",
          sortable: true,
          class: "text-center align-middle"
        }
      ],
      import_file: "",
      text: "Import",
      isDisabled: false
    };
  },
  created() {
    this.load(this.tableParams);
  },
  computed: {
    years() {
      const fromYear = new Date().getFullYear() - 5;
      const toYear = new Date().getFullYear();
      return Array.from(
        { length: toYear - fromYear },
        (value, index) => fromYear + 1 + index
      );
    },
    ...mapState("bpjsKetenagakerjaan", {
      collections: state => state.collections
    }),
    ...mapState(["errors"]),
    tableParams: {
      get() {
        return this.$store.state.humanResource.bpjsKetenagakerjaan.tableParams;
      },
      set(params) {
        this.$store.commit(
          "humanResource/bpjsKetenagakerjaan/SET_TABLE_PARAMS",
          params
        );
      }
    }
  },
  watch: {
    tableParams: {
      handler: function(params) {
        let requestParams = {
          keyword: this.keyword,
          filter: this.selectedFilterOffice,
          page: params.page,
          per_page: params.per_page,
          order_column: params.order_column,
          order_direction: params.order_direction
        };
        this.load(requestParams);
      },
      deep: true
    },
    keyword() {
      let requestParams = {
        keyword: this.keyword,
        filterOffice: this.selectedFilterOffice,
        filterMonth: this.selectedFilterMonth,
        filterYear: this.selectedFilterYear,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction
      };
      this.load(requestParams);
    },
    notify() {
      this.load(this.tableParams);
    },
    selectedFilterOffice() {
      let requestParams = {
        keyword: this.keyword,
        filterOffice: this.selectedFilterOffice,
        filterMonth: this.selectedFilterMonth,
        filterYear: this.selectedFilterYear,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction
      };
      this.load(requestParams);
    },
    selectedFilterMonth() {
      let requestParams = {
        keyword: this.keyword,
        filterOffice: this.selectedFilterOffice,
        filterMonth: this.selectedFilterMonth,
        filterYear: this.selectedFilterYear,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction
      };
      this.load(requestParams);
    },
    selectedFilterYear() {
      let requestParams = {
        keyword: this.keyword,
        filterOffice: this.selectedFilterOffice,
        filterMonth: this.selectedFilterMonth,
        filterYear: this.selectedFilterYear,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction
      };
      this.load(requestParams);
    },
    errors() {
      if ("extension" in this.errors) {
        this.resetForm();
      }
    }
  },
  methods: {
    ...mapActions("bpjsKetenagakerjaan", ["load", "store"]),
    ...mapMutations(["CLEAR_ERRORS"]),
    onFileChange(event) {
      let isConfirm = confirm("Yakin?");
      if (isConfirm == true) {
        this.import_file = event.target.files[0];
        this.proceedActionImport();
      } else {
        this.import_file = "";
      }
    },
    proceedActionImport() {
      this.CLEAR_ERRORS();

      let formData = new FormData();
      formData.append("file", this.import_file);

      this.isDisabled = true;
      this.text = "Importing...";

      this.store(formData).then(() => {
        this.$router.push({ name: "bpjs-ketenagakerjaan.index" });
        this.$message({
          type: "success",
          message: "Berhasil"
        });
        this.resetForm();
      });
    },
    resetForm() {
      this.import_file = "";
      this.isDisabled = false;
      this.text = "Import";
    },
    exportFile(type) {
      window.open(
        `/api/human-resource/bpjs-ketenagakerjaan-export-${type}?api_token=${this.$store.state.token}&keyword=${this.keyword}&office=${this.selectedFilterOffice}&month=${this.selectedFilterMonth}&year${this.selectedFilterYear}`
      );
    }
  }
};
</script>
<style scoped>
.file {
  position: relative;
  overflow: hidden;
}
input[type="file"] {
  position: absolute;
  font-size: 50px;
  opacity: 0;
  right: 0;
  top: 0;
  cursor: pointer;
}
</style>