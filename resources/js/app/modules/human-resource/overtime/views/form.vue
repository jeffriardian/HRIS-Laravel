<template>
  <div>
    <div class="form-row">
      <div class="form-group col-md-4" :class="{ 'has-error': errors.employee_id }">
        <label for>{{ $tc('employee') }}</label>
        <select-employee
          @selectedEmployee="selectedEmployee"
          :data="listOvertimeEmployee"
          :singleSelected="true"
          :sortByDepartment="true"
          v-model="form.employee_id"
        ></select-employee>
        <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
      </div>
      <div class="form-group col-md-4">
        <label for>{{ $tc('approved by') }}</label>
        <select-employee
          @selectedEmployee="selectedApprovedBy"
          :data="listSupervisorEmployee"
          :singleSelected="true"
          :sortByDepartment="false"
          :disabled="isDisabled"
          v-model="form.approved_by"
        ></select-employee>
        <p class="text-danger" v-if="errors.approved_by">{{ errors.approved_by[0] }}</p>
      </div>
      <div class="form-group col md-4">
        <label for>{{ $tc('request date') }}</label>
        <date-picker
          v-model="form.request_date"
          value-type="format"
          format="YYYY-MM-DD"
          :default-value="new Date()"
          :disabled-date="disabledAfterToday"
          type="date"
          :disabled="isDisabled"
          @change="getSchedule()"
        ></date-picker>
        <p class="text-danger" v-if="errors.request_date">{{ errors.request_date[0] }}</p>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>{{ $tc('shift') }}</label>
        <input v-model="form.shift" type="text" class="form-control form-control-sm" disabled />
        <p class="text-danger" v-if="errors.shift">{{ errors.shift[0] }}</p>
      </div>
      <div class="form-group col-md-4">
        <label>{{ $tc('schedule in') }}</label>
        <input v-model="form.schedule_in" type="text" class="form-control form-control-sm" disabled />
        <p class="text-danger" v-if="errors.schedule_in">{{ errors.schedule_in[0] }}</p>
      </div>
      <div class="form-group col-md-4">
        <label>{{ $tc('schedule out') }}</label>
        <input
          v-model="form.schedule_out"
          type="text"
          class="form-control form-control-sm"
          disabled
        />
        <p class="text-danger" v-if="errors.schedule_out">{{ errors.schedule_out[0] }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 flex-column pr-2">
        <div class="row">
          <div class="col-md-6 pr-1">
            <div class="form-group">
              <label>{{ $tc('overtime before duration') }}</label>
              <el-time-select
                size="small"
                v-model="form.overtime_before_duration"
                :picker-options="{
                  start: '00:00',
                  step: '00:30',
                  end: '23:30'
                }"
              ></el-time-select>
            </div>
          </div>
          <div class="col-md-6 pl-1">
            <div class="form-group">
              <label>{{ $tc('overtime after duration') }}</label>
              <el-time-select
                size="small"
                v-model="form.overtime_after_duration"
                :picker-options="{
                  start: '00:00',
                  step: '00:30',
                  end: '23:30'
                }"
              ></el-time-select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="mx-auto">
            <p
              class="text-danger align-items-center"
              v-if="errors.duration"
            >{{ errors.duration[0] }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 pl-1">
        <div class="form-group" :class="{ 'has-error': errors.attachment }">
          <label for>{{ $t('attachment') }}</label>
          <b-form-file accept=".png, .jpg, .jpeg" size="sm" @change="uploadPhoto($event)"></b-form-file>
          <p class="text-danger" v-if="errors.attachment">{{ errors.attachment[0] }}</p>
        </div>
      </div>
    </div>
    <!-- <div class="form-row">
      <div class="form-group col-md-4">
        <label>{{ $tc('overtime before duration') }}</label>
        <el-time-select
          size="small"
          v-model="form.overtime_before_duration"
          :picker-options="{
            start: '00:00',
            step: '00:30',
            end: '23:30'
          }"
        ></el-time-select>
        <p
          class="text-danger"
          v-if="errors.overtime_before_duration"
        >{{ errors.overtime_before_duration[0] }}</p>
      </div>
      <div class="form-group col-md-4">
        <label>{{ $tc('overtime after duration') }}</label>
        <el-time-select
          size="small"
          v-model="form.overtime_after_duration"
          :picker-options="{
            start: '00:00',
            step: '00:30',
            end: '23:30'
          }"
        ></el-time-select>
        <p
          class="text-danger"
          v-if="errors.overtime_after_duration"
        >{{ errors.overtime_after_duration[0] }}</p>
      </div>
      <p class="text-danger">tttttttttttt</p>
      <div class="form-group col-md-4" :class="{ 'has-error': errors.attachment }">
        <label for>{{ $t('attachment') }}</label>
        <b-form-file accept=".png, .jpg, .jpeg" size="sm" @change="uploadPhoto($event)"></b-form-file>
        <p class="text-danger" v-if="errors.attachment">{{ errors.attachment[0] }}</p>
      </div>
    </div>-->
    <div class="form-row">
      <div class="form-group col-12" :class="{ 'has-error': errors.description }">
        <label for>{{ $t('description') }}</label>
        <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
        <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
      </div>
    </div>
  </div>
</template>
<script>
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  components: {
    selectEmployee,
  },
  data() {
    return {
      listOvertimeEmployee: [],
      listSupervisorEmployee: [],
      isDisabled: true,
    };
  },
  computed: {
    ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
    ...mapState("overtime", {
      form: (state) => state.form, //MENGAMBIL STATE DATA
    }),
    ...mapState("employee", {
      employees: (state) => state.collectionList,
    }),
    ...mapState("dummyAttendance", {
      params: (state) => state.params,
    }),
    ...mapState("dummyAttendance", {
      schedule: (state) => state.schedule,
    }),
  },
  watch: {
    employees() {
      if (this.employees != []) {
        this.listOvertimeEmployee = this.employees.data.filter(
          (item) => item.salary_type_id == 2
        );
        this.getSupervisorEmployee(this.form.employee_id);
      }
    },
    schedule() {
      this.form.shift = this.schedule.shift;
      this.form.schedule_in = this.schedule.schedule_in;
      this.form.schedule_out = this.schedule.schedule_out;
    },
    form() {
      if (this.form.shift !== "") {
        this.isDisabled = false;
      }
    },
  },
  created() {
    this.loadEmployees({ is_active: 1, order_by: { department_id: "ASC" } });
  },
  methods: {
    ...mapActions("employee", { loadEmployees: "loadList" }),
    ...mapActions("dummyAttendance", { loadSchedule: "show" }),
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations("overtime", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
    ...mapMutations("dummyAttendance", ["ASSIGN_DATA"]),
    ...mapMutations("dummyAttendance", ["CLEAR_SCHEDULE"]),
    uploadPhoto(event) {
      this.form.attachment = event.target.files[0];
    },
    selectedEmployee(value) {
      this.resetSchedule();
      this.form.employee_id = value;
      if (this.form.employee_id === "") {
        this.isDisabled = true;
        this.ASSIGN_DATA(null);
      } else {
        this.isDisabled = false;
        this.getSupervisorEmployee(value);
      }
    },
    getSupervisorEmployee(id) {
      this.listSupervisorEmployee = [];

      const indexSelectedEmployee = this.employees.data.findIndex(
        (x) => x.id == id
      );

      const parentFromPositionIdSelectedEmployee = this.employees.data[
        indexSelectedEmployee
      ].position.parent_id;

      let result = [];

      const that = this;
      function rekursif(parent_id) {
        that.employees.data.filter((employee) => {
          const checkDuplicateResult = result.findIndex(
            (res) => res.nik == employee.nik
          );
          if (employee.position_id == parent_id && checkDuplicateResult == -1) {
            result.push(employee);
            if (employee.position.parent_id != 0)
              rekursif(employee.position.parent_id);
          }
        });
      }
      rekursif(parentFromPositionIdSelectedEmployee);
      this.listSupervisorEmployee = result;
    },
    getSchedule() {
      this.params.employee_id = this.form.employee_id;
      this.params.request_date = this.form.request_date;

      this.loadSchedule();
    },
    resetSchedule() {
      this.form.request_date = "";
      this.form.shift = "";
      this.form.schedule_in = "";
      this.form.schedule_out = "";
    },
    disabledAfterToday(date) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      return date > new Date(today.getTime());
    },
    selectedApprovedBy(value) {
      this.form.approved_by = value;
    },
  },
  //KETIKA PAGE INI DITINGGALKAN MAKA
  destroyed() {
    //FORM DI BERSIHKAN
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
    this.CLEAR_SCHEDULE();
  },
};
</script>
<style scoped>
.el-date-editor.el-input,
.el-date-editor.el-input__inner {
  width: 100%;
}

input[type="text"]:disabled {
  background: #dddddd6c;
}
</style>
