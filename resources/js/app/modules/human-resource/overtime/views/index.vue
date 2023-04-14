<template>
  <div>
    <div class="card">
      <div class="card-body">
        <b-tabs content-class="mt-3">
          <b-tab active>
            <template v-slot:title>
              <i class="fas fa-file-alt"></i>
              Overtime By SPL
            </template>
            <overtimeSPL></overtimeSPL>
          </b-tab>
          <b-tab @click="getAutoOvertime">
            <template v-slot:title>
              <i class="fas fa-tachometer-alt"></i>
              Auto Overtime
            </template>
            <overtime></overtime
          ></b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapMutations, mapState } from "vuex";
import overtimeSPL from "./tabs/overtime-spl/index";
import overtime from "./tabs/overtime";
export default {
  components: {
    overtimeSPL,
    overtime,
  },
  methods: {
    ...mapActions("overtime", ["loadAutoOvertime"]),
    getAutoOvertime() {
      this.loadAutoOvertime(
        this.$store.state.humanResource.overtime.tableAutoOvertimeParams
      );
    },
  },
};
</script>