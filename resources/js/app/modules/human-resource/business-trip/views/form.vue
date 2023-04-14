<template>
  <div>
    <div class="form-row">
      <div class="form-group col-md-3" :class="{ 'has-error': errors.employee_id }">
        <label for>{{ $tc('employee') }}</label>
        <v-select
          v-model="form.employee_id"
          :options="employees.data"
          label="name"
          :reduce="name => name.id"
          :class="{ 'border-danger': errors.employee_id }"
          :disabled="isProcess"
        ></v-select>
        <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.destination }">
        <label for>{{ $t('destination') }}</label>
        <input
          minlength="16"
          maxlength="255"
          class="form-control form-control-sm"
          :class="{ 'border-danger': errors.destination }"
          v-model="form.destination"
          :readonly="$route.name == 'businessTrips.edit' || isProcess"
        />
        <p class="text-danger" v-if="errors.destination">{{ errors.destination[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.join_at }">
        <label for>{{ $tc('departure date') }}</label>
        <!-- buat datepicker disini -->
        <date-picker
          format="YYYY-MM-DD"
          value-type="format"
          type="date"
          :class="{ 'border-danger': errors.departure_date }"
          v-model="form.departure_date"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.departure_date">{{ errors.departure_date[0] }}</p>
      </div>
      <div class="form-group col-md-3" :class="{ 'has-error': errors.join_at }">
        <label for>{{ $tc('back date') }}</label>
        <!-- buat datepicker disini -->
        <date-picker
          format="YYYY-MM-DD"
          value-type="format"
          type="date"
          :class="{ 'border-danger': errors.back_date }"
          v-model="form.back_date"
          :disabled="isProcess"
        />
        <p class="text-danger" v-if="errors.back_date">{{ errors.back_date[0] }}</p>
      </div>
      <div class="form-group col-md-3" v-if="isEdit">
        <label for>Is Reimburse</label>
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="checked"
            id="isReimburse"
            :disabled="isProcess"
          />
          <label class="form-check-label" for="isReimburse">Yes</label>
        </div>
      </div>
      <div class="form-group col-md-3" v-if="checked && isEdit">
        <label for>Reimburse Type</label>
        <b-form-radio-group
          :options="reimburseTypeOptions"
          v-model="form.reimburse_type"
          :disabled="isProcess"
        >
          <!-- <b-form-invalid-feedback>Please select one</b-form-invalid-feedback> -->
        </b-form-radio-group>
      </div>
    </div>
    <div class="form-group" :class="{ 'has-error': errors.purpose }">
      <label for>{{ $t('purpose') }}</label>
      <textarea cols="5" rows="5" class="form-control" v-model="form.purpose" :disabled="isProcess"></textarea>
      <p class="text-danger" v-if="errors.purpose">{{ errors.purpose[0] }}</p>
    </div>
    <div class="card m-b-30" v-if="isEdit">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-9">
            <h5 class="card-title">{{ $tc('transportation',2) }}</h5>
          </div>
          <div class="col-3">
            <button
              type="button"
              @click="addTransportation()"
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
            <th width="25%">{{ $t('transportation') }}</th>
            <th>{{ $t('cost') }}</th>
            <th v-if="isEdit">{{ $tc('image', 0) }}</th>
            <th width="70px">{{ $t('action') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(row, index) in form.transportations"
            :key="index"
            class="justify-content-between align-items-center"
          >
            <td class="align-middle">
              <v-select
                v-model="row.transportation_id"
                :options="transportations.data"
                label="name"
                :reduce="name => name.id"
                :class="{ 'border-danger': errors[`transportations.${index}.transportation_id`] }"
                :disabled="isProcess"
              ></v-select>
              <p
                class="text-danger"
                v-if="errors[`transportations.${index}.transportation_id`]"
              >{{ errors[`transportations.${index}.transportation_id`][0] }}</p>
            </td>
            <td class="align-middle">
              <input
                type="text"
                class="form-control form-control-sm"
                :class="{ 'border-danger': errors[`transportations.${index}.cost`] }"
                v-model="row.cost"
                :disabled="isProcess"
              />
              <p
                class="text-danger"
                v-if="errors[`transportations.${index}.cost`]"
              >{{ errors[`transportations.${index}.cost`][0] }}</p>
            </td>
            <td v-if="isEdit">
              <!-- <input
                type="file"
                id
                class="form-control-file form-control-sm"
                accept="image/jpg, image/jpeg, image/png"
                @change="uploadImage($event, index, 'transportations')"
                :class="{ 'border-danger': errors[`transportations.${index}.image`] }"
                multiple
              />-->
              <ul class="el-upload-list el-upload-list--picture-card">
                <li
                  v-for="(image, i) in imageList.transportations[`transportation.${index}`]"
                  :key="i"
                  class="el-upload-list__item"
                >
                  <img :src="image" class="image-thumbnail" />
                  <span class="el-upload-list__item-actions">
                    <div class="list-action">
                      <!-- <span class="el-upload-list__item-preview" @click="clickZoom">zoom</span> -->
                      <span
                        class="el-upload-list__item-delete"
                        @click="clickDelete('transportation', index, i)"
                      >
                        <i class="mdi mdi-trash-can-outline"></i>
                      </span>
                    </div>
                  </span>
                  <span
                    class="text-danger"
                    v-if="errors[`transportations.${index}.images.${i}`]"
                  >{{ errors[`transportations.${index}.images.${i}`][0] }}</span>
                </li>
              </ul>
              <div
                class="el-upload el-upload--picture-card"
                tabindex="0"
                @click="triggerRef(index, 'transportation')"
              >
                <i class="el-icon-plus"></i>
                <input
                  type="file"
                  name="file"
                  class="el-upload__input"
                  :ref="'transportation' + index"
                  @change="getImage($event, index, 'transportation')"
                  :disabled="isProcess"
                />
              </div>
            </td>
            <td class="text-center">
              <a
                href="javascript:void(0)"
                @click="removeTransportation(index)"
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
    <div class="card m-b-30" v-if="isEdit">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-9">
            <h5 class="card-title">{{ $tc('allowance',2) }}</h5>
          </div>
          <div class="col-3">
            <button
              type="button"
              @click="addReceiptParameter()"
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
            <th width="25%">{{ $t('allowance') }}</th>
            <th>{{ $t('cost') }}</th>
            <th v-if="isEdit">{{ $tc('image',0) }}</th>
            <th width="70px">{{ $t('action') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(row, index) in form.parameters"
            :key="index"
            class="justify-content-between align-items-center"
          >
            <td class="align-middle">
              <v-select
                v-model="row.receipt_parameter_id"
                :options="parameters.data"
                label="name"
                :reduce="name => name.id"
                :class="{ 'border-danger': errors[`parameters.${index}.receipt_parameter_id`] }"
                :disabled="isProcess"
              ></v-select>
              <p
                class="text-danger"
                v-if="errors[`parameters.${index}.receipt_parameter_id`]"
              >{{ errors[`parameters.${index}.receipt_parameter_id`][0] }}</p>
            </td>
            <td class="align-middle">
              <input
                type="text"
                class="form-control form-control-sm"
                :class="{ 'border-danger': errors[`parameters.${index}.cost`] }"
                v-model="row.cost"
                :disabled="isProcess"
              />
              <p
                class="text-danger"
                v-if="errors[`parameters.${index}.cost`]"
              >{{ errors[`parameters.${index}.cost`][0] }}</p>
            </td>
            <td v-if="isEdit">
              <!-- <input
                type="file"
                id
                class="form-control-file form-control-sm"
                accept="image/jpg, image/jpeg, image/png"
                @change="uploadImage($event, index, 'allowance')"
                :class="{ 'border-danger': errors[`parameters.${index}.image`] }"
                multiple
              />-->
              <ul class="el-upload-list el-upload-list--picture-card">
                <li
                  v-for="(image, i) in imageList.parameters[`parameter.${index}`]"
                  :key="i"
                  class="el-upload-list__item"
                >
                  <img :src="image" class="image-thumbnail" />
                  <span class="el-upload-list__item-actions">
                    <div class="list-action">
                      <!-- <span class="el-upload-list__item-preview" @click="clickZoom">zoom</span> -->
                      <span
                        class="el-upload-list__item-delete"
                        @click="clickDelete('parameter', index, i)"
                      >
                        <i class="mdi mdi-trash-can-outline"></i>
                      </span>
                    </div>
                  </span>
                </li>
              </ul>
              <div
                class="el-upload el-upload--picture-card"
                tabindex="0"
                @click="triggerRef(index, 'parameter')"
              >
                <i class="el-icon-plus"></i>
                <input
                  type="file"
                  name="file"
                  class="el-upload__input"
                  :ref="'parameter' + index"
                  :disabled="isProcess"
                  @change="getImage($event, index, 'parameter')"
                />
              </div>
              <p
                class="text-danger"
                v-if="errors[`parameters.${index}.images`]"
              >{{ errors[`parameters.${index}.images`][0] }}</p>
            </td>
            <td class="text-center">
              <a
                href="javascript:void(0)"
                @click="removeReceiptParameter(index)"
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
    <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value="1" unchecked-value="0">
                {{ $t('status') }}
            </b-form-checkbox>
    </div>-->
    <div class="form-group" :class="{ 'has-error': errors.business_trip_result }" v-if="isEdit">
      <label for>{{ $t('business trip result') }}</label>
      <textarea
        cols="5"
        rows="5"
        class="form-control"
        v-model="form.business_trip_result"
        :disabled="isProcess"
      ></textarea>
      <p class="text-danger" v-if="errors.business_trip_result">{{ errors.business_trip_result[0] }}</p>
    </div>
  </div>
</template>
<script>
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  props: ["isProcess"],
  data() {
    return {
      reimburseTypeOptions: [
        { text: "Payroll", value: "payroll" },
        { text: "Cash", value: "cash" },
      ],
      isEdit: false,
      checked: false,
      imageList: {
        transportations: {},
        parameters: {},
      },
    };
  },
  mounted() {
    this.loadEmployees();
    this.loadTransportations();
    this.loadReceiptParameters();
    this.addTransportation();
    this.addReceiptParameter();
    this.disabledForm();
  },
  computed: {
    ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
    ...mapState("businessTrip", {
      form: (state) => state.form, //MENGAMBIL STATE DATA
    }),
    ...mapState("employee", {
      employees: (state) => state.collectionList,
    }),
    ...mapState("transportation", {
      transportations: (state) => state.collectionList,
    }),
    ...mapState("receiptParameter", {
      parameters: (state) => state.collectionList,
    }),
  },
  watch: {
    checked() {
      if (this.checked == false) {
        this.form.reimburse_type = null;
      }
    },
    form() {
      this.checked = this.form.reimburse_type != null ? true : false;
    },
  },
  beforeMount() {},
  methods: {
    ...mapMutations(["CLEAR_ERRORS"]),
    ...mapMutations("businessTrip", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
    ...mapActions("employee", { loadEmployees: "loadList" }),
    ...mapActions("transportation", { loadTransportations: "loadList" }),
    ...mapActions("receiptParameter", { loadReceiptParameters: "loadList" }),
    //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
    addTransportation() {
      this.form.transportations.push({
        transportation_id: null,
        cost: null,
        images: null,
      });
    },
    //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
    removeTransportation(index) {
      if (this.form.transportations.length > 1) {
        this.form.transportations.splice(index, 1);
      }
    },
    //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
    addReceiptParameter() {
      this.form.parameters.push({
        receipt_parameter_id: null,
        cost: null,
        images: null,
      });
    },
    //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
    removeReceiptParameter(index) {
      if (this.form.parameters.length > 1) {
        this.form.parameters.splice(index, 1);
        ``;
      }
    },
    // uploadImage(event, index, param) {
    //   if (param === "allowance") {
    //     let images = new Array();
    //     event.target.files.forEach((element, i) => {
    //       images.push(event.target.files[i]);
    //     });
    //     this.form.parameters[index].images = images;
    //   } else {
    //     let images = new Array();
    //     event.target.files.forEach((element, i) => {
    //       images.push(event.target.files[i]);
    //     });
    //     this.form.transportations[index].images = images;
    //   }
    // },
    disabledForm() {
      if (this.$route.name == "businessTrips.edit") {
        this.isEdit = true;
      }
    },
    triggerRef(ref, type) {
      let key = `${type}${ref}`;
      this.$refs[key][0].click();
    },
    getImage(event, index, type) {
      let inputImage = event.target;
      if (inputImage.files && inputImage.files[0]) {
        let reader = new FileReader();
        const key = `${type}.${index}`;
        const imageType =
          type == "transportation" ? "transportations" : "parameters";
        reader.onload = (e) => {
          // jika ada key di imagelist di object imageType
          if ([key] in this.imageList[imageType]) {
            this.imageList[imageType][key].push(e.target.result); // maka push to array
          } else {
            this.$set(this.imageList[imageType], [key], [e.target.result]); // set ke object imageType
          }
        };
        reader.readAsDataURL(inputImage.files[0]);

        if (this.form[imageType][index].images == null) {
          this.form[imageType][index].images = [event.target.files[0]];
        } else {
          this.form[imageType][index].images.push(event.target.files[0]);
        }
      }
    },
    // clickZoom() {
    //   alert("zoom");
    // },
    clickDelete(type, indexRow, indexImage) {
      const imageType =
        type == "transportation" ? "transportations" : "parameters";
      const key = `${type}.${indexRow}`;
      this.imageList[imageType][key].splice(indexImage, 1);
      this.form[imageType][indexRow].images.splice(indexImage, 1);
      if (this.form[imageType][indexRow].images.length == 0) {
        this.form[imageType][indexRow].images = null;
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
    "v-select": vSelect,
  },
};
</script>

<style>
.el-upload-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.el-upload-list--picture-card {
  margin: 0;
  display: inline;
  vertical-align: top;
}

.el-upload-list__item {
  transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
  font-size: 14px;
  color: #606266;
  line-height: 1.8;
  margin-top: 5px;
  position: relative;
  box-sizing: border-box;
  border-radius: 4px;
  width: 100%;
}
.el-upload-list__item:first-child {
  margin-top: 10px;
}
.el-upload-list--picture-card .el-upload-list__item {
  overflow: hidden;
  background-color: #fff;
  border: 1px solid #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  width: 148px;
  height: 148px;
  margin: 0 8px 8px 0;
  display: inline-block;
}
.el-upload {
  display: inline-block;
  text-align: center;
  cursor: pointer;
  outline: 0;
}
.el-upload--picture-card {
  background-color: #fbfdff;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  width: 148px;
  height: 148px;
  line-height: 146px;
  vertical-align: top;
}
.el-upload--picture-card i {
  font-size: 28px;
  color: #8c939d;
}
.el-icon-plus:before {
  content: "\E6D9";
}
.el-upload__input {
  display: none;
}
/* input {
  overflow: visible;
} */
input[type="file" i] {
  appearance: initial;
  background-color: initial;
  cursor: default;
  align-items: baseline;
  color: inherit;
  text-align: start !important;
  padding: initial;
  border: initial;
}
.image-thumbnail {
  width: 148px;
  height: 148px;
}

.el-upload-list--picture-card .el-upload-list__item-actions {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  transition: 0.5s ease;
  background-color: rgba(0, 0, 0, 0.5);
  cursor: pointer;
}
.el-upload-list--picture-card .el-upload-list__item-actions:hover {
  opacity: 0.8;
}
.el-upload-list--picture-card .el-upload-list__item-actions .list-action {
  color: white;
  margin-right: 5px;
  top: 50%;
  left: 50%;
  position: absolute;
  transform: translate(-50%, -50%);
}
.el-upload-list--picture-card .el-upload-list__item-actions .list-action span {
  margin-right: 5px;
}
</style>
