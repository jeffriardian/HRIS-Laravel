<template>
  <div>
    <div class="form-group" :class="{ 'has-error': errors.name }">
      <label for>{{ $t('name') }}</label>
      <input
        type="text"
        class="form-control"
        :class="{ 'border-danger': errors.name }"
        v-model="form.name"
      />
      <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
    </div>
    <div class="form-group">
      <label for>{{ $tc('weekday') }}</label>
      <div class="d-flex flex-column">
        <div class="d-flex justify-content-between">
          <div class="d-flex flex-column">
            <el-time-select
              v-model="form.details.weekday_in"
              placeholder="In"
              format="HH:mm"
              size="small"
              :picker-options="{
                start: '00:00',
                step: '00:30',
                end: '23:30'
            }"
              :default-value="defaultTime"
              @change="totalDuration('weekday')"
            ></el-time-select>
            <p
              class="text-danger"
              v-if="errors['details.weekday_in']"
            >{{ errors['details.weekday_in'][0] }}</p>
          </div>
          <div class="d-flex flex-column">
            <el-time-select
              v-model="form.details.weekday_out"
              placeholder="Out"
              format="HH:mm"
              size="small"
              :picker-options="{
                start: '00:00',
                step: '00:30',
                end: '23:30'
            }"
              :default-value="defaultTime"
              @change="totalDuration('weekday')"
            ></el-time-select>
            <p
              class="text-danger"
              v-if="errors['details.weekday_out']"
            >{{ errors['details.weekday_out'][0] }}</p>
          </div>
        </div>
        <div class="d-flex justify-content-center mt-1">{{ totalDurationWeekday }}</div>
      </div>
    </div>
    <div class="form-group">
      <label for>{{ $tc('weekend') }}</label>
      <div class="d-flex flex-column">
        <div class="d-flex justify-content-between">
          <div class="d-flex flex-column">
            <el-time-select
              v-model="form.details.weekend_in"
              placeholder="In"
              format="HH:mm"
              size="small"
              :picker-options="{
                    start: '00:00',
                    step: '00:30',
                    end: '23:30'
                }"
              :default-value="defaultTime"
              @change="totalDuration('weekend')"
            ></el-time-select>
            <p
              class="text-danger"
              v-if="errors['details.weekend_in']"
            >{{ errors['details.weekend_in'][0] }}</p>
          </div>
          <div class="d-flex flex-column">
            <el-time-select
              v-model="form.details.weekend_out"
              placeholder="Out"
              format="HH:mm"
              size="small"
              :picker-options="{
                    start: '00:00',
                    step: '00:30',
                    end: '23:30'
                }"
              :default-value="defaultTime"
              @change="totalDuration('weekend')"
            ></el-time-select>
            <p
              class="text-danger"
              v-if="errors['details.weekend_out']"
            >{{ errors['details.weekend_out'][0] }}</p>
          </div>
        </div>
        <div class="d-flex justify-content-center mt-1">{{ totalDurationWeekend }}</div>
      </div>
    </div>
    <div class="form-group">
      <b-form-checkbox v-model="form.is_active" value="1" unchecked-value="0">{{ $t('status') }}</b-form-checkbox>
    </div>
  </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  mixins: ["calculateTotalDuration"],
  props: ["modelId"],
  data() {
    return {
      defaultTime: new Date().setHours(0, 0, 0, 0),
      totalDurationWeekday: "0 jam",
      totalDurationWeekend: "0 jam",
    };
  },
  computed: {
    ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
    ...mapState("officeHour", {
      form: (state) => state.formSpecial, //MENGAMBIL STATE DATA
    }),
  },
  methods: {
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations("officeHour", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
    //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
    totalDuration(type) {
      let start;
      let end;

      let total;

      let totalHour;
      let totalMinutes;

      let result;

      if (type === "weekday") {
        if (
          this.form.details.weekday_in !== null &&
          this.form.details.weekday_out !== null
        ) {
          start = this.form.details.weekday_in;
          end = this.form.details.weekday_out;
          total = this.calculateTotalDuration(start, end);

          totalHour = total.split(".")[0];
          totalMinutes = total.split(".")[1];
        }
      } else {
        if (
          this.form.details.weekend_in !== null &&
          this.form.details.weekend_out !== null
        ) {
          start = this.form.details.weekend_in;
          end = this.form.details.weekend_out;
          total = this.calculateTotalDuration(start, end);

          totalHour = total.split(".")[0];
          totalMinutes = total.split(".")[1];
        }
      }

      if (totalHour == undefined || totalMinutes == undefined) {
        result = "0 jam";
      } else if (totalHour == 0 && totalMinutes == 0) {
        result = "24 jam";
      } else if (totalHour == 0 && totalMinutes != 0) {
        result = `${totalMinutes} menit`;
      } else if (totalHour != 0 && totalMinutes == 0) {
        result = `${totalHour} jam`;
      } else {
        result = `${totalHour} jam ${totalMinutes} menit`;
      }

      if (type === "weekday") {
        this.totalDurationWeekday = result;
      } else {
        this.totalDurationWeekend = result;
      }
    },
  },
  destroyed() {
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
  },
};
</script>