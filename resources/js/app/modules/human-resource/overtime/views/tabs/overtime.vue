<template>
  <div class="card">
    <div class="card-body">
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
      </div>
      <b-table
        :sort-by.sync="tableAutoOvertimeParams.order_column"
        :sort-desc.sync="tableAutoOvertimeParams.order_direction"
        responsive="sm"
        thead-class="thead-light"
        small
        hover
        bordered
        :items="dataAutoOvertime.data"
        :fields="fields"
        show-empty
      >
        <template v-slot:head()="data">{{ $tc(data.label) }}</template>
        <template v-slot:cell(index)="row">{{
          tableAutoOvertimeParams.page !== 1
            ? row.index +
              1 +
              tableAutoOvertimeParams.per_page *
                (tableAutoOvertimeParams.page - 1)
            : row.index + 1
        }}</template>
        <template v-slot:cell(employee_id)="row"
          >{{ row.item.employee.nik }} - {{ row.item.employee.name }}</template
        >
        <template v-slot:cell(L1)="row">{{
          row.item.L1 | formatHour
        }}</template>
        <template v-slot:cell(L2)="row">{{
          row.item.L2 | formatHour
        }}</template>
        <template v-slot:cell(L3)="row">{{
          row.item.L3 | formatHour
        }}</template>
        <template v-slot:cell(total_times)="row">{{
          row.item.total_times | formatTotalTime
        }}</template>
      </b-table>
    </div>
    <div
      class="card-footer bg-white d-flex justify-content-between align-items-center"
    >
      <div>
        <b-form-select
          v-model="tableAutoOvertimeParams.per_page"
          :options="tableAutoOvertimeParams.pageOptions"
        ></b-form-select>
      </div>
      <div>
        <p v-if="dataAutoOvertime.data">
          {{
            $t("table summary", {
              from: dataAutoOvertime.meta.from,
              to: dataAutoOvertime.meta.to,
              total: dataAutoOvertime.meta.total,
            })
          }}
        </p>
      </div>
      <div>
        <b-pagination
          v-model="tableAutoOvertimeParams.page"
          pills
          class="v-step-4"
          :total-rows="dataAutoOvertime.meta.total"
          :per-page="dataAutoOvertime.meta.per_page"
          v-if="dataAutoOvertime.data && dataAutoOvertime.data.length > 0"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapMutations, mapState } from "vuex";
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
          key: "employee_id",
          label: "employee",
          thStyle: "text-align: center;",
          sortable: true,
        },
        {
          key: "date",
          label: "date",
          thStyle: "text-align: center;",
          tdClass: "text-center",
          sortable: true,
        },
        {
          key: "L1",
          label: "l1",
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "L2",
          label: "l2",
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "L3",
          label: "l3",
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
        {
          key: "total_times",
          label: "total time",
          thStyle: "text-align: center;",
          tdClass: "text-center",
        },
      ],
    };
  },
  computed: {
    ...mapState("overtime", {
      dataAutoOvertime: (state) => state.dataAutoOvertime,
    }),
    ...mapState("notification", {
      notify: (state) => state.notification,
    }),
    tableAutoOvertimeParams: {
      get() {
        //MENGAMBIL VALUE PAGE DARI VUEX MODULE
        return this.$store.state.humanResource.overtime.tableAutoOvertimeParams;
      },
      set(params) {
        this.$store.commit(
          "humanResource/overtime/SET_AUTO_OVERTIME_PAGE",
          params
        );
      },
    },
  },
  created() {
    this.loadAutoOvertime(this.tableAutoOvertimeParams);
  },
  watch: {
    tableAutoOvertimeParams: {
      handler: function (params) {
        let requestParams = {
          keyword: this.keyword,
          page: params.page,
          per_page: params.per_page,
          order_column: params.order_column,
          order_direction: params.order_direction,
        };
        this.loadAutoOvertime(requestParams);
      },
      deep: true,
    },
    keyword() {
      let requestParams = {
        keyword: this.keyword,
        page: 1,
        per_page: this.tableAutoOvertimeParams.per_page,
        order_column: this.tableAutoOvertimeParams.order_column,
        order_direction: this.tableAutoOvertimeParams.order_direction,
      };
      this.loadAutoOvertime(requestParams);
    },
    notify() {
      this.loadAutoOvertime(this.tableAutoOvertimeParams);
    },
  },
  methods: {
    ...mapActions("overtime", ["loadAutoOvertime"]),
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
  },
};
</script>