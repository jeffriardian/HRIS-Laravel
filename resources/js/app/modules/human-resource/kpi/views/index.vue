<template>
  <div class="card">
    <div class="card-body">
      <div class="row mb-2">
        <div class="col-12">
          <b-tabs content-class="mt-3">
            <b-tab title="Laporan" @click="tabClick('reports')" active>
              <report></report>
            </b-tab>
            <b-tab @click="tabClick('employees')" title="Karyawan">
              <employee></employee>
            </b-tab>
            <b-tab @click="tabClick('formulas')" title="Rumus">
              <formula></formula>
            </b-tab>
          </b-tabs>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import report from "./report";
import employee from "./employee";
import formula from "./formula";

import { mapActions, mapState } from "vuex";
export default {
  components: {
    report,
    employee,
    formula,
  },
  computed: {
    ...mapState("kpi", {
      tableParams: (state) => state.tableParams,
    }),
    ...mapState("employee", {
      employees: (state) => state.collectionList,
    }),
  },
  created() {
    this.loadKpiReports();
  },
  methods: {
    ...mapActions("kpi", [
      "loadKpiReports",
      "loadKpiEmployees",
      "loadKpiFormulas",
    ]),
    ...mapActions("employee", { loadCollectionEmployees: "loadList" }),
    tabClick(param) {
      if (param == "reports") {
        this.loadKpiReports(this.tableParams);
      } else if (param == "employees") {
        this.loadCollectionEmployees();
        this.loadKpiEmployees(this.tableParams);
      } else {
        this.loadKpiFormulas(this.tableParams);
      }
    },
  },
};
</script>