<template>
  <div>
    <div class="row">
      <div class="col-4">
        <div class="form-group">
          <label>Form Excel</label>
          <b-form-file
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            size="sm"
            @change="uploadFileExcel($event)"
          ></b-form-file>
          <p class="text-danger" v-if="errors.excel ">{{ errors.excel[0] }}</p>
        </div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label>{{ $t('approved by') }}</label>
          <select-employee
            @selectedEmployee="selectedApprovedBy"
            :data="listAllInEmployee"
            :singleSelected="true"
            v-model="formUpload.approved_by"
          ></select-employee>
          <p class="text-danger" v-if="errors.approved_by">{{ errors.approved_by[0] }}</p>
        </div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <label>{{ $tc('attachment') }}</label>

          <b-form-file accept=".png, .jpg, .jpeg" size="sm" @change="uploadFileAttachment($event)"></b-form-file>
          <p class="text-danger" v-if="errors.attachment">{{ errors.attachment[0] }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  components: {
    selectEmployee,
  },
  data() {
    return {
      listSelectedEmployee: [],
      listAllInEmployee: [],
    };
  },
  computed: {
    ...mapState(["errors"]),
    ...mapState("employee", {
      employees: (state) => state.collectionList,
    }),
    ...mapState("overtime", {
      formUpload: (state) => state.formUpload, //MENGAMBIL STATE DATA
    }),
  },
  created() {
    this.loadEmployees();
  },
  watch: {
    employees() {
      if (this.employees != []) {
        this.listAllInEmployee = this.employees.data.filter(
          (item) => item.salary_type_id != 2
        );
      }
    },
    errors() {
      const stringErrors = Object.keys(this.errors).toString();
      if (
        stringErrors.includes("nik") ||
        stringErrors.includes("request_date") ||
        stringErrors.includes("duration") ||
        stringErrors.includes("description")
      ) {
        this.SET_ERRORS({
          excel: [
            "Field Nik / Request Date / Start Time / End Time / One of Overtime Duration / Description is required.",
          ],
        });
      }
    },
  },
  methods: {
    ...mapActions("employee", { loadEmployees: "loadList" }),
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations(["SET_ERRORS"]),
    ...mapMutations("overtime", ["CLEAR_FORM"]),
    selectedApprovedBy(value) {
      this.formUpload.approved_by = value;
    },
    uploadFileAttachment(event) {
      this.formUpload.attachment = event.target.files[0];
    },
    uploadFileExcel(event) {
      this.formUpload.excel = event.target.files[0];
    },
  },
  destroyed() {
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
  },
};
</script>