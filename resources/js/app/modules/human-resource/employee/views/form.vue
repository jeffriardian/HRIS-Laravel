<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-12">
                <h5 class="card-title">{{ $tc('personal data',2) }}</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <div v-if="form.image_preview != null">
            <img
              :src="imagePreview ? imagePreview : '/storage/images/employee-photos/' + form.image_preview"
              class="img-fluid"
            />
          </div>
          <div v-if="form.image_preview == null">
            <img :src="imagePreview ? imagePreview : '/images/default.jpg'" class="img-fluid" />
          </div>
        </div>
        <div class="form-group">
          <div class="form-group" :class="{ 'has-error': errors.photo }">
            <input
              type="file"
              class="form-control-file"
              @change="uploadPhoto($event)"
              :class="{ 'border-danger': errors.photo }"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="form-row">
          <div class="form-group col-md-12" :class="{ 'has-error': errors.name }">
            <label for>{{ $t('name') }}</label>
            <input
              type="text"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.name }"
              v-model="form.name"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
          </div>
        </div>
        <div class="form-row">
          <b-form-group class="col-md-6" :label="$t('gender')">
            <b-form-radio-group v-model="form.gender" :options="genderOptions"></b-form-radio-group>
          </b-form-group>
          <div class="form-group col-md-6" :class="{ 'has-error': errors.nik }">
            <label for>NIK</label>
            <input
              type="text"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.nik }"
              v-model="form.nik"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.nik">{{ errors.nik[0] }}</p>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6" :class="{ 'has-error': errors.ktp }">
            <label for>KTP</label>
            <input
              minlength="16"
              maxlength="16"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.ktp }"
              v-model="form.ktp"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.ktp">{{ errors.ktp[0] }}</p>
          </div>
          <div class="form-group col-md-6" :class="{ 'has-error': errors.kk }">
            <label for>KK</label>
            <input
              minlength="16"
              maxlength="16"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.kk }"
              v-model="form.kk"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.kk">{{ errors.kk[0] }}</p>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6" :class="{ 'has-error': errors.npwp }">
            <label for>NPWP</label>
            <input
              minlength="15"
              maxlength="15"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.npwp }"
              v-model="form.npwp"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.npwp">{{ errors.npwp[0] }}</p>
          </div>
          <div class="form-group col-md-6" :class="{ 'has-error': errors.sim }">
            <label for>SIM</label>
            <input
              minlength="12"
              maxlength="12"
              class="form-control form-control-sm"
              :class="{ 'border-danger': errors.sim }"
              v-model="form.sim"
              :disabled="isProcess"
            />
            <p class="text-danger" v-if="errors.sim">{{ errors.sim[0] }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.religion_id }">
        <label for>{{ $tc('religion') }}</label>
        <v-select
          v-model="form.religion_id"
          :options="religions.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.religion_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.religion_id">{{ errors.religion_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.email }">
        <label for>{{ $t('email') }}</label>
        <input
          type="email"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.email }"
          v-model="form.email"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.phone }">
        <label for>{{ $t('phone') }}</label>
        <input
          type="text"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.phone }"
          v-model="form.phone"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.blood_type_id }">
        <label for>{{ $tc('blood type') }}</label>
        <v-select
          v-model="form.blood_type_id"
          :options="bloodTypes.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.blood_type_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.blood_type_id">{{ errors.blood_type_id[0] }}</p>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.marital_status_id }">
        <label for>{{ $tc('marital status') }}</label>
        <v-select
          v-model="form.marital_status_id"
          :options="maritalStatuses.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.marital_status_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.marital_status_id">{{ errors.marital_status_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.birth_place }">
        <label for>{{ $tc('birth place') }}</label>
        <input
          type="text"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.phone }"
          v-model="form.birth_place"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.birth_place">{{ errors.birth_place[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.birth_at }">
        <label for>{{ $tc('birth date') }}</label>
        <date-picker
          format="YYYY-MM-DD"
          value-type="format"
          type="date"
          :class="{ 'border-danger': errors.birth_at }"
          v-model="form.birth_at"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.birth_at">{{ errors.birth_at[0] }}</p>
      </div>
    </div>
    <hr />
    <div class="card m-b-30">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-12">
            <h5 class="card-title">{{ $tc('staffing data',2) }}</h5>
          </div>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.company_id }">
        <label for>{{ $tc('company') }}</label>
        <v-select
          v-model="form.company_id"
          :options="companies.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.company_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.company_id">{{ errors.company_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.department_id }">
        <label for>{{ $tc('department') }}</label>
        <v-select
          v-model="form.department_id"
          :options="departments.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.department_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.department_id">{{ errors.department_id[0] }}</p>
      </div>
      <div class="form-group col-md-3">
        <label for>{{ $tc('position')}}</label>
        <v-select
          v-model="form.position_id"
          :options="positions.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.position_id }"
          :disabled="$route.name == 'employees.edit' || isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.position_id">{{ errors.position_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.payroll_type_id }">
        <label for>{{ $tc('payroll type') }}</label>
        <v-select
          v-model="form.payroll_type_id"
          :options="payrollTypes.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.payroll_type_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.payroll_type_id">{{ errors.payroll_type_id[0] }}</p>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.salary_type_id }">
        <label for>{{ $tc('salary type') }}</label>
        <v-select
          v-model="form.salary_type_id"
          :options="salaryTypes.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.salary_type_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.salary_type_id">{{ errors.salary_type_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.join_at }">
        <label for>{{ $tc('join date') }}</label>
        <!-- buat datepicker disini -->
        <!-- <datetime
          type="date"
          v-model="form.join_at"
          format="dd-MM-yyyy"
          :disabled="$route.name == 'employees.edit' || isProcess"
        >
          <template slot="button-cancel">
            <fa :icon="['far', 'times']"></fa>Cancel
          </template>
          <template slot="button-confirm">
            <fa :icon="['fas', 'check-circle']"></fa>Confirm
          </template>
        </datetime>-->
        <!-- {{ form.join_at }} -->

        <date-picker
          :class="{ 'border-danger': errors.join_at }"
          format="YYYY-MM-DD"
          value-type="format"
          type="date"
          v-model="form.join_at"
          :disabled="$route.name == 'employees.edit' || isProcess"
        />
        <p class="text-danger" v-if="errors.join_at">{{ errors.join_at[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.employee_status_id }">
        <label for>{{ $tc('employee status') }}</label>
        <v-select
          v-model="form.employee_status_id"
          @input="getEndDate()"
          :options="employeeStatuses.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.employee_status_id }"
          :disabled="$route.name == 'employees.edit' || isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.employee_status_id">{{ errors.employee_status_id[0] }}</p>
        <!-- {{ form.join_at | moment("YYYY-MM-DD") | moment("add", "7 days") | moment("DD-MM-YYYY") }} -->
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.leave_at }">
        <label for>{{ $t('end date') }}</label>
        <input
          type="leave_at"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.leave_at }"
          v-model="form.leave_at"
          disabled
        />
        <p class="text-danger" v-if="errors.leave_at">{{ errors.leave_at[0] }}</p>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.office_hour_id }">
        <label for>{{ $tc('office hour') }}</label>
        <v-select
          v-model="form.office_hour_id"
          :options="officeHours.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.office_hour_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.office_hour_id">{{ errors.office_hour_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.ptkp_code }">
        <label for>PTKP Code</label>
        <v-select
          v-model="form.ptkp_code"
          :options="ptkps.data"
          label="code"
          :reduce="code => code.code"
          :class="{ 'border-danger': errors.ptkp_code }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.ptkp_code">{{ errors.ptkp_code[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.pin }">
        <label for>PIN</label>
        <input
          type="text"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.pin }"
          v-model="form.pin"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.pin">{{ errors.pin[0] }}</p>
      </div>
    </div>
    <hr />
    <div class="card m-b-30">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-9">
            <h5 class="card-title">{{ $tc('contact',2) }}</h5>
          </div>
          <div class="col-3">
            <button
              type="button"
              @click="addContact()"
              class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
              v-b-tooltip.hover
              :title="$tc('add')"
            >
              <i class="mdi mdi-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <table class="table table-bordered table-sm table-hover">
        <thead>
          <tr>
            <th width="25%">{{ $t('type') }}</th>
            <th>{{ $t('description') }}</th>
            <th width="70px">{{ $t('action') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(row, index) in form.contacts"
            :key="index"
            class="justify-content-between align-items-center"
          >
            <td>
              <v-select
                v-model="row.type"
                :options="contactTypeOptions"
                label="text"
                :reduce="text => text.value"
                :class="{ 'border-danger': errors[`${index}.type`] }"
                :disabled="isProcess"
              ></v-select>
              <p class="text-danger" v-if="errors[`${index}.type`]">{{ errors[`${index}.type`][0] }}</p>
            </td>
            <td>
              <input
                type="text"
                class="form-control form-control-sm"
                :class="{ 'border-danger': errors[`${index}.description`] }"
                v-model="row.description"
                :disabled="isProcess"
              />
              <p
                class="text-danger"
                v-if="errors[`${index}.description`]"
              >{{ errors[`${index}.description`][0] }}</p>
            </td>
            <td class="text-center">
              <a
                href="javascript:void(0)"
                @click="removeContact(index)"
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
    <div class="form-group" :class="{ 'has-error': errors.address }">
      <label for>{{ $t('address') }}</label>
      <textarea cols="5" rows="5" class="form-control" v-model="form.address" :disabled="isProcess"></textarea>
      <p class="text-danger" v-if="errors.address">{{ errors.address[0] }}</p>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.address_ktp }">
      <label for>{{ $t('KTP address') }}</label>
      <textarea
        cols="5"
        rows="5"
        class="form-control"
        v-model="form.address_ktp"
        :disabled="isProcess"
      ></textarea>
      <p class="text-danger" v-if="errors.address_ktp">{{ errors.address_ktp[0] }}</p>
    </div>
    <div class="form-group">
      <b-form-checkbox
        v-model="form.is_active"
        value="1"
        unchecked-value="0"
        :disabled="isProcess"
      >{{ $t('status') }}</b-form-checkbox>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  props: ["isProcess"],
  data() {
    return {
      genderOptions: [
        { text: this.$tc("man"), value: 1 },
        { text: this.$tc("woman"), value: 0 },
      ],
      contactTypeOptions: [
        { text: this.$tc("phone"), value: 1 },
        { text: this.$tc("email"), value: 2 },
      ],
      imagePreview: "",
      isLoadingProcess: false,
    };
  },
  created() {
    this.addContact();
    this.loadDepartments();
    this.loadReligions();
    this.loadBloodTypes();
    this.loadPayrollTypes();
    this.loadMaritalStatuses();
    this.loadEmployeeStatuses();
    this.loadCompanies();
    this.getEndDate();
    this.loadPositions();
    this.loadOfficeHours();
    this.loadPtkps();
    this.loadSalaryTypes();
  },
  computed: {
    ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
    ...mapState("employee", {
      form: (state) => state.form, //MENGAMBIL STATE DATA
    }),
    ...mapState("department", {
      departments: (state) => state.collectionList,
    }),
    ...mapState("religion", {
      religions: (state) => state.collectionList,
    }),
    ...mapState("bloodType", {
      bloodTypes: (state) => state.collectionList,
    }),
    ...mapState("payrollType", {
      payrollTypes: (state) => state.collectionList,
    }),
    ...mapState("maritalStatus", {
      maritalStatuses: (state) => state.collectionList,
    }),
    ...mapState("employeeStatus", {
      employeeStatuses: (state) => state.collectionList,
    }),
    ...mapState("company", {
      companies: (state) => state.collectionList,
    }),
    ...mapState("position", {
      positions: (state) => state.collectionList,
    }),
    ...mapState("officeHour", {
      officeHours: (state) => state.collectionList,
    }),
    ...mapState("ptkp", {
      ptkps: (state) => state.collectionList,
    }),
    ...mapState("salaryType", {
      salaryTypes: (state) => state.collectionList,
    }),
  },
  methods: {
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations("employee", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
    ...mapActions("department", { loadDepartments: "loadList" }),
    ...mapActions("religion", { loadReligions: "loadList" }),
    ...mapActions("bloodType", { loadBloodTypes: "loadList" }),
    ...mapActions("payrollType", { loadPayrollTypes: "loadList" }),
    ...mapActions("maritalStatus", { loadMaritalStatuses: "loadList" }),
    ...mapActions("employeeStatus", { loadEmployeeStatuses: "loadList" }),
    ...mapActions("company", { loadCompanies: "loadList" }),
    ...mapActions("position", { loadPositions: "loadList" }),
    ...mapActions("officeHour", { loadOfficeHours: "loadList" }),
    ...mapActions("ptkp", { loadPtkps: "loadList" }),
    ...mapActions("salaryType", { loadSalaryTypes: "loadList" }),
    //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
    addContact() {
      this.form.contacts.push({ type: null, description: null });
    },
    //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
    removeContact(index) {
      if (this.form.contacts.length > 1) {
        this.form.contacts.splice(index, 1);
      }
    },
    getEndDate() {
      var joinDate = moment(this.form.join_at).format("YYYY-MM-DD");
      var endDate = moment(joinDate)
        .add(this.form.employee_status_id, "M")
        .format("DD-MM-YYYY");
      this.form.leave_at = endDate;

      // alert(this.form.employee_status_id);
      // alert(futureMonth);
    },
    uploadPhoto(event) {
      let inputImage = event.target;
      if (inputImage.files && inputImage.files[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        };
        reader.readAsDataURL(inputImage.files[0]);
      }
      this.form.photo = event.target.files[0];
    },
  },
  //KETIKA PAGE INI DITINGGALKAN MAKA
  destroyed() {
    //FORM DI BERSIHKAN
    this.CLEAR_ERRORS();
    this.CLEAR_FORM();
  },
  components: {
    "v-select": vSelect,
  },
};
</script>
