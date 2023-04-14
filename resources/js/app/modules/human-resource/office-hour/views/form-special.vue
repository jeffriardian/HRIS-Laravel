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
    <div class="card m-b-50">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-9">
            <h5 class="card-title">Detail</h5>
          </div>
          <div class="col-3">
            <button
              type="button"
              @click="addDetail()"
              class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
              v-b-tooltip.hover
              :title="$tc('add')"
            >
              <i class="mdi mdi-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
          <thead>
            <tr>
              <th>Weekday</th>
              <th>Weekend</th>
              <th>{{ $t('action') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(row, index) in form.details"
              :key="index"
              class="justify-content-between align-items-center"
            >
              <td>
                <div class="d-flex flex-column">
                  <div>
                    <div class="d-flex justify-content-center">
                      <div class="d-flex flex-column">
                        <el-time-select
                          v-model="form.details[index].weekday_in"
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
                          v-if="errors[`details.${index}.weekday_in`]"
                        >{{ errors[`details.${index}.weekday_in`][0] }}</p>
                      </div>
                      <div class="d-flex flex-column ml-2">
                        <el-time-select
                          v-model="form.details[index].weekday_out"
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
                          v-if="errors[`details.${index}.weekday_out`]"
                        >{{ errors[`details.${index}.weekday_out`][0] }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-center mt-1">{{ totalDurationWeekday }}</div>
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <div>
                    <div class="d-flex justify-content-center">
                      <div class="d-flex flex-column">
                        <el-time-select
                          v-model="form.details[index].weekend_in"
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
                          v-if="errors[`details.${index}.weekend_in`]"
                        >{{ errors[`details.${index}.weekend_in`][0] }}</p>
                      </div>
                      <div class="d-flex flex-column ml-2">
                        <el-time-select
                          v-model="form.details[index].weekend_out"
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
                          v-if="errors[`details.${index}.weekend_out`]"
                        >{{ errors[`details.${index}.weekend_out`][0] }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-center mt-1">{{ totalDurationWeekend }}</div>
                </div>
              </td>
              <td class="text-center">
                <a
                  href="javascript:void(0)"
                  @click="removeDetail(index)"
                  class="text-danger"
                  v-b-tooltip.hover
                  :title="$tc('delete')"
                >
                  <i class="mdi mdi-trash-can-outline font-size-16"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="form-group">
      <b-form-checkbox v-model="form.is_active" value="1" unchecked-value="0">{{ $t('status') }}</b-form-checkbox>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import { Datetime } from "vue-datetime";
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";
import "vue-datetime/dist/vue-datetime.js";

import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import { BFormFile } from "bootstrap-vue";

export default {
  mixins: ["calculateTotalDuration"],
  data() {
    return {
      defaultTime: new Date().setHours(0, 0, 0, 0),
      totalDurationWeekday: "0 jam",
      totalDurationWeekend: "0 jam",
    };
  },
  created() {
    this.addDetail();
  },
  props: ["modelId"],
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
          this.form.details[0].weekday_in !== null &&
          this.form.details[0].weekday_out !== null
        ) {
          start = this.form.details[0].weekday_in;
          end = this.form.details[0].weekday_out;
          total = this.calculateTotalDuration(start, end);

          totalHour = total.split(".")[0];
          totalMinutes = total.split(".")[1];
        }
      } else {
        if (
          this.form.details[0].weekend_in !== null &&
          this.form.details[0].weekend_out !== null
        ) {
          start = this.form.details[0].weekend_in;
          end = this.form.details[0].weekend_out;
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
    addDetail() {
      this.form.details.push({
        weekday_in: null,
        weekday_out: null,
        weekend_in: null,
        weekend_out: null,
      });
    },
    //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
    removeDetail(index) {
      if (this.form.details.length > 1) {
        this.form.details.splice(index, 1);
      }
    },
  },
  //KETIKA PAGE INI DITINGGALKAN MAKA
  destroyed() {
    //FORM DI BERSIHKAN
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
  },
  components: {
    "b-form-file": BFormFile,
    "v-select": vSelect,
    datetime: Datetime,
  },
};
</script>
<style scoped>
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 160px;
}
</style>
