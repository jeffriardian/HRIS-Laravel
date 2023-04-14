<template>
    <div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group" :class="{ 'has-error': errors.photo }">
                    <div v-if="form.image_preview != null">
                        <img
                            :src="
                                imagePreview
                                    ? imagePreview
                                    : '/storage/images/employee-photos/' +
                                      form.image_preview
                            "
                            class="img-fluid"
                        />
                    </div>
                    <div v-if="form.image_preview == null">
                        <img
                            :src="
                                imagePreview
                                    ? imagePreview
                                    : '/images/default-user.png'
                            "
                            @click="uploadPhoto()"
                            class="img-fluid"
                        />
                        <input
                            type="file"
                            class="d-none"
                            ref="image"
                            @change="getImage($event)"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-row">
                    <div
                        class="form-group col-md-12"
                        :class="{ 'has-error': errors.name }"
                    >
                        <label for="">{{ $t("name") }}</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{ 'border-danger': errors.name }"
                            v-model="form.name"
                            v-on:keypress="isLetter($event)"
                        />
                        <p class="text-danger" v-if="errors.name">
                            {{ errors.name[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-md-6"
                        :class="{
                            'has-error': errors.identity_card_number
                        }"
                    >
                        <label for="">{{ $t("KTP") }}</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{
                                'border-danger': errors.identity_card_number
                            }"
                            maxlength="16"
                            v-int
                            v-on:keypress="numberOnly(event)"
                            v-model="form.identity_card_number"
                        />
                        <p
                            class="text-danger"
                            v-if="errors.identity_card_number"
                        >
                            {{ errors.identity_card_number[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-md-6"
                        :class="{ 'has-error': errors.email }"
                    >
                        <label for="">{{ $t("Email") }}</label>
                        <input
                            type="email"
                            class="form-control"
                            placeholder="example@gmail.com"
                            :class="{ 'border-danger': errors.email }"
                            v-model="form.email"
                        />
                        <span v-if="msg.email" class="text-danger">{{
                            msg.email
                        }}</span>
                        <p class="text-danger" v-if="errors.email">
                            {{ errors.email[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-md-12"
                        :class="{ 'has-error': errors.phone_number }"
                    >
                        <label for="">{{ $t("Phone Number") }}</label>
                        <div class="row">
                            <span class="input-group-text col-2 ml-3">
                                <img
                                    src="/assets/images/flags/id.svg"
                                    class="ml-2"
                                    :width="25"
                                />
                            </span>
                            <span class="input-group-text col-1.5">+62</span>
                            <!-- <input
                                type="text"
                                class="form-control col-md-7"
                                v-imask="mask"
                                :value="value"
                                @accept="onAccept"
                            /> -->
                            <input
                                type="text"
                                v-int
                                maxlength="11"
                                class="form-control col-md-7"
                                v-model="form.phone_number"
                                placeholder="812345xxx"
                            />
                        </div>
                        <p class="text-danger" v-if="errors.phone_number">
                            {{ errors.phone_number[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-md-12"
                        :class="{ 'has-error': errors.position_id }"
                    >
                        <label for="">{{ $tc("Position") }}</label>
                        <multiselect
                            size="sm"
                            v-model="dataPosition"
                            :options="positionList"
                            group-values="positions"
                            group-label="department"
                            :group-select="false"
                            placeholder="Type to search"
                            track-by="name"
                            label="name"
                            ><span slot="noResult"
                                >Oops! No elements found. Consider changing the
                                search query.</span
                            ></multiselect
                        >
                        <!--
                        <pre
                            class="language-json"
                        ><code>{{ value  }}</code></pre>
                        <v-select
                            v-model="form.position_id"
                            :options="positions.data"
                            label="name"
                            :reduce="name => name.id"
                            :class="{ 'border-danger': errors.position_id }"
                        ></v-select> -->
                        <p class="text-danger" v-if="errors.position_id">
                            {{ errors.position_id[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-md-12"
                        :class="{ 'has-error': errors.company_id }"
                    >
                        <label for="">{{ $tc("Company") }}</label>
                        <v-select
                            v-model="form.company_id"
                            :options="companies.data"
                            label="name"
                            :reduce="name => name.id"
                            :class="{ 'border-danger': errors.company_id }"
                        ></v-select>
                        <p class="text-danger" v-if="errors.company_id">
                            {{ errors.company_id[0] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row"></div>
        <div class="form-row">
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.birth_place }"
            >
                <label for="">{{ $t("Birth Place") }}</label>
                <v-select
                    v-model="form.birth_place"
                    :options="cities.data"
                    label="nama_kabkota"
                    :reduce="nama_kabkota => nama_kabkota.nama_kabkota"
                    :class="{ 'border-danger': errors.birth_place }"
                ></v-select>
                <p class="text-danger" v-if="errors.birth_place">
                    {{ errors.birth_place[0] }}
                </p>
            </div>
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.birth_at }"
            >
                <label for="">{{ $tc("Date of Birth") }}</label>
                <date-picker
                    :class="{ 'border-danger': errors.birth_at }"
                    v-model="form.birth_at"
                    format="YYYY-MM-DD"
                    value-type="format"
                    type="date"
                    :default-value="new Date()"
                    :disabled-date="disabledAfterToday"
                />
                <p class="text-danger" v-if="errors.birth_at">
                    {{ errors.birth_at[0] }}
                </p>
            </div>
        </div>
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h5 class="card-title">{{ $tc("File Upload", 2) }}</h5>
                    </div>
                    <div class="col-3">
                        <button
                            type="button"
                            @click="addFile()"
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
                        <th width="50%">&nbsp;{{ $t("File Upload") }}</th>
                        <th>{{ $t("Note") }}</th>
                        <th width="70px">{{ $t("action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in form.files"
                        :key="index"
                        class="justify-content-between align-items-center"
                    >
                        <td>
                            <input
                                type="file"
                                class="form-control form-control"
                                @change="uploadFile($event, index)"
                                :class="{
                                    'border-danger': errors[`${index}.file`]
                                }"
                            />
                            <br />
                        </td>
                        <td>
                            <textarea
                                class="form-control form-control"
                                :class="{
                                    'border-danger': errors[`${index}.note`]
                                }"
                                v-model="row.note"
                            ></textarea>
                        </td>
                        <td class="text-cenxter">
                            <a
                                href="javascript:void(0)"
                                @click="removeFile(index)"
                                class="text-danger"
                                v-b-tooltip.hover
                                :title="$tc('delete')"
                            >
                                <i
                                    class="mdi mdi-trash-can-outline font-size-16"
                                ></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="form-row">
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.recruitment_step_id }"
            >
                <label for="">{{ $t("Test Step") }}</label>
                <v-select
                    v-model="form.recruitment_step_id"
                    :options="recruitmentSteps.data"
                    label="name"
                    :reduce="name => name.id"
                    :class="{ 'border-danger': errors.recruitment_step_id }"
                ></v-select>
                <p class="text-danger" v-if="errors.recruitment_step_id">
                    {{ errors.recruitment_step_id[0] }}
                </p>
            </div>
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.recruitment_date }"
            >
                <label for="">{{ $tc("Schedule Test Date") }}</label>
                <date-picker
                    :class="{ 'border-danger': errors.recruitment_date }"
                    v-model="form.recruitment_date"
                    format="YYYY-MM-DD"
                    value-type="format"
                    type="date"
                    :default-value="new Date()"
                    :disabled-date="disabledBeforeToday"
                />
                <p class="text-danger" v-if="errors.recruitment_date">
                    {{ errors.recruitment_date[0] }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { VueAvatar } from "vue-avatar-editor-improved";
import { Datetime } from "vue-datetime";
import onlyInt from "vue-input-only-number";
import moment from "moment";
import "vue-datetime/dist/vue-datetime.css";
import "vue-datetime/dist/vue-datetime.js";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";
import Multiselect from "vue-multiselect";
Vue.use(onlyInt);

export default {
    data() {
        return {
            dataPosition: [],
            scoreChecked: [],
            rotation: 0,
            scale: 1,
            number: "",
            email: "",
            msg: [],
            imagePreview: "",
            eventFile: {},
            value: " ",
            mask: {
                mask: "{+62}00000000000",
                lazy: false
            },
            disabledDates: {
                to: new Date(Date.now() - 8640000)
            },
            selectedFile: ""
        };
    },
    directives: {
        imask: IMaskDirective
    },
    props: ["modelId"],
    created() {
        this.loadPositions();
        this.loadCompanies();
        this.loadCities();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("recruitment", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("position", {
            positions: state => state.collectionList
        }),
        ...mapState("company", {
            companies: state => state.collectionList
        }),
        ...mapState("city", {
            cities: state => state.collectionList
        }),
        ...mapState("recruitmentStep", {
            recruitmentSteps: state => state.collectionList
        }),
        positionList() {
            let positions = _.groupBy(this.positions.data, function(position) {
                    return position.department_code;
                }),
                groupedPosition = _.map(positions, function(position, index) {
                    return {
                        department: index,
                        positions: position
                    };
                });
            console.log(groupedPosition);
            return groupedPosition;
        }
    },
    watch: {
        email(value) {
            // binding this to the data value in the email input
            this.email = value;
            this.form.email = value;
            this.validateEmail(value);
        },
        dataPosition() {
            if (typeof this.dataPosition == "object") {
                this.form.position_id = this.dataPosition.id;
                console.log(this.form.position_id);
            }
        }
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("recruitment", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("position", { loadPositions: "loadListPositionGroup" }),
        ...mapActions("company", { loadCompanies: "loadList" }),
        ...mapActions("city", { loadCities: "loadList" }),
        // ...mapActions("recruitmentStep", { loadCompanies: "loadList" }),
        ...mapActions("recruitment", ["store"]), //PANGGIL ACTIONS store
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        disabledBeforeToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date < today;
        },
        numberOnly(evt) {
            var charCode = evt.which ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
            return true;
        },
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            if (/^[A-Za-z- ]+$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        validateEmail(value) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.msg["email"] = "";
            } else {
                this.msg["email"] = "Invalid Email Address";
            }
        },
        onAccept(e) {
            const maskRef = e.detail;
            this.value = maskRef.value;
            this.form.phone_number = maskRef.unmaskedValue;
        },
        uploadPhoto(event) {
            this.$refs.image.click();
        },
        getImage() {
            let inputImage = event.target;
            if (inputImage.files && inputImage.files[0]) {
                let reader = new FileReader();
                reader.onload = e => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(inputImage.files[0]);
            }
            // this.$refs.image.click();
            this.form.photo = event.target.files[0];
        },
        onSelectFile(event) {
            this.form.photo = event[0];
            console.log(this.form.photo);
        },
        uploadFile(event, index) {
            console.log("file", event);
            this.form.files[index].caption = event.target.files[0];
            console.log("Candidate File", this.form.files);
        },
        addFile() {
            // console.log(this.form.files);
            this.form.files.push({
                caption: null,
                note: null
            });
        },
        removeFile(index) {
            if (this.form.files.length > 1) {
                this.form.files.splice(index, 1);
            }
        },
        saveClicked() {
            var img = this.$refs.vueavatar.getImageScaled();
            console.log(img.toDataURL());
            this.$refs.image.src = img.toDataURL();
        },
        onImageReady() {
            console.log(this.$refs.vueavatar.getImage());
            this.scale = 1;
            this.rotation = 0;
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        "v-select": vSelect,
        VueAvatar: VueAvatar,
        datetime: Datetime,
        Multiselect: Multiselect
    }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
