<template>
  <div class="card">
    <div class="card-body">
      <div class="row mb-2">
        <div class="col-5">
          <div class="search-box mr-2 mb-2 d-inline-block">
            <div class="position-relative">
              <input type="text" class="form-control" :placeholder="$t('search')" v-model="keyword" />
              <i class="bx bx-search-alt search-icon"></i>
            </div>
          </div>
        </div>
        <div class="col-7">
          <div class="d-flex">
            <span class="text-black mr-2">Filter</span>
            <b-form-select
              v-model="selectedOptionFilterByDate"
              :options="optionFilterByDate"
              size="sm"
              class="mr-2"
              @change="changeFilterDate(selectedOptionFilterByDate)"
            ></b-form-select>
            <date-picker
              v-model="selectedDate"
              format="YYYY-MM-DD"
              value-type="format"
              type="date"
              v-if="selectedOptionFilterByDate == null"
              :disabled="selectedOptionFilterByDate == null"
            />
            <date-picker
              v-model="selectedDate"
              format="YYYY-MM-DD"
              value-type="format"
              type="date"
              v-if="selectedOptionFilterByDate == 'date'"
            />
            <date-picker
              v-model="selectedDate"
              format="YYYY-MM"
              value-type="format"
              type="month"
              v-if="selectedOptionFilterByDate == 'month'"
            />
            <date-picker
              v-model="selectedDate"
              format="YYYY"
              value-type="format"
              type="year"
              v-if="selectedOptionFilterByDate == 'year'"
            />
          </div>
          <!-- <div class="col-4"></div>
          <div class="col-5"></div>-->
        </div>
      </div>
      <div class="row">
        <div class="col-12">
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
            <template v-slot:cell(actions)="data">
              <a href="#" @click.prevent="preview(data.item)" data-toggle="tooltip" title="Detail">
                <i class="mdi mdi-eye font-size-16"></i>
              </a>
            </template>
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
    <b-modal
      title="Detail Log Aktivitas"
      ref="bv-modal-preview"
      footer-class="p-2"
      size="lg"
      ok-only
      no-close-on-backdrop
    >
      <div class="row bg-light-gray">
        <div class="col-md-2 text-left mb-2">
          <b>Log</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewData.log_name }}</div>
        <div class="col-md-2 text-left mb-2">
          <b>ID/Code</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewData.subject_id }}</div>
        <div class="col-md-2 text-left mb-2">
          <b>Proses</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewData.description }}</div>
        <div class="col-md-2 text-left mb-2">
          <b>User</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewDataName }}</div>
        <div class="col-md-2 text-left mb-2">
          <b>Level</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewData.causer_level }}</div>
        <div class="col-md-2 text-left mb-2">
          <b>Tanggal</b>
        </div>
        <div class="col-md-10 text-left mb-2">: {{ previewData.created_at }}</div>

        <div class="col-md-12 text-left mb-2">
          <div>
            <b>Aktivitas</b>
          </div>
          <div class="preview" v-html="previewDataRow"></div>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import moment from "moment";
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  data() {
    return {
      keyword: "",
      fields: [
        {
          key: "index",
          label: "#",
          thStyle: "text-align:center; width: 35px;",
          class: "text-center align-middle",
        },
        {
          key: "log_name",
          label: "activity",
          sortable: true,
          class: "text-center align-middle",
        },
        {
          key: "description",
          label: "process",
          sortable: true,
          class: "text-center align-middle",
        },
        {
          key: "user.employee.name",
          label: "name",
          sortable: true,
          class: "text-center align-middle",
        },
        {
          key: "causer_level",
          label: "level",
          sortable: true,
          class: "text-center align-middle",
        },
        {
          key: "created_at",
          label: "date",
          sortable: true,
          class: "text-center align-middle",
        },
        {
          key: "actions",
          label: "action",
          class: "text-center align-middle",
        },
      ],
      selectedOptionFilterByDate: "month",
      optionFilterByDate: [
        { text: "Semua Waktu", value: null },
        { text: "Tanggal", value: "date" },
        { text: "Bulan", value: "month" },
        { text: "Tahun", value: "year" },
      ],
      selectedDate: moment(new Date()).format("YYYY-MM"),
      previewDataRow: [],
      previewData: [],
      previewDataName: "",
    };
  },
  mounted() {
    this.load(this.tableParams);
  },
  computed: {
    ...mapState("activityLog", {
      collections: (state) => state.collections,
    }),
    tableParams: {
      get() {
        return this.$store.state.humanResource.activityLog.tableParams;
      },
      set(params) {
        this.$store.commit(
          "humanResource/activityLog/SET_TABLE_PARAMS",
          params
        );
      },
    },
  },
  watch: {
    tableParams: {
      handler(params) {
        let requestParams = {
          keyword: this.keyword,
          date: this.selectedDate,
          filterByDate: this.selectedOptionFilterByDate,
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
      this.sendSearchAndFilter();
    },
    selectedDate(date) {
      this.sendSearchAndFilter();
    },
    notify() {
      this.load(this.tableParams);
    },
  },
  methods: {
    ...mapActions("activityLog", ["load"]),
    changeFilterDate(filter) {
      if (filter == "date") {
        this.selectedDate = moment(new Date()).format("YYYY-MM-DD");
      } else if (filter == "month") {
        this.selectedDate = moment(new Date()).format("YYYY-MM");
      } else if (filter == "year") {
        this.selectedDate = moment(new Date()).format("YYYY");
      } else {
        this.selectedDate = null;
      }
    },
    sendSearchAndFilter() {
      let requestParams = {
        keyword: this.keyword,
        date: this.selectedDate,
        filterByDate: this.selectedOptionFilterByDate,
        page: 1,
        per_page: this.tableParams.per_page,
        order_column: this.tableParams.order_column,
        order_direction: this.tableParams.order_direction,
      };

      this.load(requestParams);
    },
    nestedLoop(obj) {
      const res = {};
      function recurse(obj, current) {
        for (const key in obj) {
          let value = obj[key];
          if (value != undefined) {
            if (value && typeof value === "object") {
              recurse(value, key);
            } else {
              // Do your stuff here to var value
              res[key] = value;
            }
          }
        }
      }
      recurse(obj);
      return res;
    },
    preview(item) {
      this.previewDataName = `${item.user.employee.nik} - ${item.user.employee.name}`;
      this.previewData = item;
      var rows = '<div class="row bg-light-gray">';
      const itemProperties = JSON.parse(item.properties);
      for (const property in itemProperties) {
        const title = null;
        if (property == "old") {
          title = "Previous";
        } else {
          title = "New";
        }
        rows += '<div class="col-md-6 text-left mt-2"><b>' + title + "</b>";
        rows += '<div class="table-wrapper-scroll-y my-custom-scrollbar">';
        rows += '<table class="table table-bordered">';
        const objectFromProperty = this.nestedLoop(itemProperties[property]);
        rows += `
        <thead>
          <tr>
            <th scope="col">Key</th>
            <th scope="col">Value</th>
          </tr>
        </thead>
        <tbody>
        `;
        for (const [key, value] of Object.entries(objectFromProperty)) {
          rows += `
          <tr>
            <td>${key}</td>
            <td>${value}</td>
          </tr>`;
        }
        rows += "</tbody>";
        rows += "</table>";
        rows += "</div>";
        rows += "</div>";
      }
      rows += "</div>";
      this.previewDataRow = rows;
      this.$refs["bv-modal-preview"].show();
    },
  },
};
</script>
<style scoped>
.preview >>> .my-custom-scrollbar {
  position: relative;
  height: 190px;
  overflow: auto;
}
.preview >>> .table-wrapper-scroll-y {
  display: block;
}
</style>