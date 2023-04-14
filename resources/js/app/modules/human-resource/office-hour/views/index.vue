<template>
  <div class="card">
    <div class="card-body">
      <b-tabs content-class="mt-3">
        <b-tab active>
          <template v-slot:title>
            <i class="fas fa-clock"></i>
            Office Hour
          </template>
          <div class="card">
            <office-hour></office-hour>
          </div>
        </b-tab>
        <b-tab>
          <template v-slot:title>
            <i class="far fa-clock"></i>
            Office Hour Special
          </template>
          <office-hour-special></office-hour-special>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</template>
<script>
import officeHour from "./tabs/office-hour";
import officeHourSpecial from "./tabs/office-hour-special";
export default {
  components: {
    officeHour,
    officeHourSpecial,
  },
};
</script>
