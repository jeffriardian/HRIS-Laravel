<template>
    <div>
        <div class="form-row">
            <div
                class="form-group col-6"
                :class="{ 'has-error': errors.employee_id }"
            >
                <label for>{{ $tc("incident category") }}</label>
                <v-select
                    v-model="form.incident_category_id"
                    :options="incidentCategories.data"
                    label="name"
                    :reduce="name => name.id"
                    :class="{ 'border-danger': errors.incident_category_id }"
                ></v-select>
                <p class="text-danger" v-if="errors.incident_category_id">
                    {{ errors.incident_category_id[0] }}
                </p>
            </div>
            <div
                class="form-group col-6"
                :class="{ 'has-error': errors.date_of_incident }"
            >
                <label for>{{ $tc("date of incident") }}</label>
                <!-- buat datepicker disini -->
                <DatePicker
                    :class="{
                        'border-danger': errors.start_date
                    }"
                    value-type="format"
                    format="YYYY-MM-DD"
                    v-model="form.date_of_incident"
                    :disabled-date="disabledAfterToday"
                />
                <p class="text-danger" v-if="errors.date_of_incident">
                    {{ errors.date_of_incident[0] }}
                </p>
            </div>
        </div>
        <b-tabs content-class="mt-3">
            <b-tab active>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Witness
                </template>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-9"></div>
                                    <div class="col-3">
                                        <button
                                            type="button"
                                            @click="addWitness()"
                                            class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                            v-b-tooltip.hover
                                            :title="$tc('add')"
                                        >
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table
                                class="table table-bordered table-sm table-hover"
                            >
                                <thead>
                                    <tr>
                                        <th>&nbsp;{{ $t("External") }}</th>
                                        <th>{{ $t("Witness") }}</th>
                                        <th>{{ $t("Description") }}</th>
                                        <th width="70px">{{ $t("action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(row,
                                        index) in form.actor_witness"
                                        :key="index"
                                        class="justify-content-between align-items-center"
                                    >
                                        <td>
                                            <b-form-checkbox
                                                v-model="row.employeeStatus"
                                                :value="true"
                                                :unchecked-value="false"
                                                >{{
                                                    $t("Yes")
                                                }}</b-form-checkbox
                                            >
                                        </td>
                                        <td>
                                            <select-external-employee
                                                v-if="
                                                    row.employeeStatus == true
                                                "
                                                @selectedEmployee="
                                                    selectedExternalWitnesses(
                                                        index,
                                                        ...arguments
                                                    )
                                                "
                                                :data="externalEmployees.data"
                                                :singleSelected="true"
                                                v-model="row.external"
                                            ></select-external-employee>
                                            <select-employee
                                                v-else
                                                @selectedEmployee="
                                                    selectedWitnesses(
                                                        index,
                                                        ...arguments
                                                    )
                                                "
                                                :data="employees.data"
                                                :singleSelected="true"
                                                v-model="row.witnesses"
                                            ></select-employee>
                                            <br />
                                        </td>
                                        <td>
                                            <textarea
                                                class="form-control"
                                                v-model="row.description"
                                            ></textarea>
                                        </td>
                                        <td class="text-cenxter">
                                            <a
                                                href="javascript:void(0)"
                                                @click="removeWitnesess(index)"
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
                    </div>
                </div>
            </b-tab>
            <b-tab>
                <template v-slot:title>
                    <div v-if="errors.suspect_actor" class="text-danger">
                        <span class="mdi mdi-alert-circle text-danger"></span
                        >&nbsp;Suspect
                    </div>
                    <div v-else>
                        <span class="fa fa-users"></span>&nbsp;Suspect
                    </div>
                </template>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <p class="text-danger" v-if="errors.suspect_actor">
                            {{ errors.suspect_actor[0] }}
                        </p>
                        <div class="card m-b-30">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-9"></div>
                                    <div class="col-3">
                                        <button
                                            type="button"
                                            @click="addSuspect()"
                                            class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                                            v-b-tooltip.hover
                                            :title="$tc('add')"
                                        >
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table
                            class="table table-bordered table-sm table-hover"
                        >
                            <thead>
                                <tr>
                                    <th>&nbsp;{{ $t("External") }}</th>
                                    <th>{{ $t("Suspects") }}</th>
                                    <th>{{ $t("Description") }}</th>
                                    <th width="70px">{{ $t("action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(row, index) in form.actor_suspects"
                                    :key="index"
                                    class="justify-content-between align-items-center"
                                >
                                    <td>
                                        <b-form-checkbox
                                            v-model="row.employeeStatus"
                                            :value="true"
                                            :unchecked-value="false"
                                            >{{ $t("Yes") }}</b-form-checkbox
                                        >
                                    </td>
                                    <td>
                                        <select-external-employee
                                            v-if="row.employeeStatus == true"
                                            @selectedEmployee="
                                                selectedExternalSuspected(
                                                    index,
                                                    ...arguments
                                                )
                                            "
                                            :data="externalEmployees.data"
                                            :singleSelected="true"
                                            v-model="row.external"
                                        ></select-external-employee>
                                        <select-employee
                                            v-else
                                            @selectedEmployee="
                                                selectedSuspected(
                                                    index,
                                                    ...arguments
                                                )
                                            "
                                            :data="employees.data"
                                            :singleSelected="true"
                                            v-model="row.suspects"
                                        ></select-employee>
                                        <p
                                            class="text-danger"
                                            v-if="
                                                errors[
                                                    `suspect_actor.${index}.actor`
                                                ]
                                            "
                                        >
                                            {{
                                                errors[
                                                    `suspect_actor.${index}.actor`
                                                ][0]
                                            }}
                                        </p>
                                        <br />
                                    </td>
                                    <td>
                                        <textarea
                                            class="form-control"
                                            v-model="row.description"
                                        ></textarea>
                                    </td>
                                    <td class="text-cenxter">
                                        <a
                                            href="javascript:void(0)"
                                            @click="removeSuspects(index)"
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
                </div>
            </b-tab>
        </b-tabs>
        <div class="form-group" :class="{ 'has-error': errors.no_order }">
            <b-card class="col-md-12" bg-variant="light">
                <label for>{{ $t("Property Damage") }}</label>
                <b-form-checkbox
                    class="ml-2"
                    inline
                    v-model="form.orderStatus"
                    :value="true"
                    :unchecked-value="false"
                    switch
                    >{{ $tc("Yes") }}</b-form-checkbox
                >
                <div class="row">
                    <div class="col-md-3">
                        <v-select
                            v-if="form.orderStatus"
                            v-model="form.interrogation_report_type_id"
                            :options="interrogationReportTypes.data"
                            label="name"
                            :reduce="name => name.id"
                            :class="{
                                'border-danger':
                                    errors.interrogation_report_type_id
                            }"
                        ></v-select>
                    </div>
                    <div class="col-md-9">
                        <v-select
                            v-if="
                                form.orderStatus &&
                                    form.interrogation_report_type_id == 5
                            "
                            v-model="form.typeable_id"
                            :options="inventories.data"
                            label="name"
                            :reduce="name => name.id"
                            :class="{ 'border-danger': errors.typeable_id }"
                        ></v-select>
                        <v-select
                            v-if="
                                form.orderStatus &&
                                    form.interrogation_report_type_id == 6
                            "
                            v-model="form.typeable_id"
                            :options="machines.data"
                            label="code"
                            :reduce="code => code.id"
                            :class="{ 'border-danger': errors.typeable_id }"
                        ></v-select>
                    </div>
                </div>
            </b-card>
        </div>
        <div
            class="form-group"
            :class="{ 'has-error': errors.incident_chronologic }"
        >
            <label for>{{ $t("Incident Chronologic") }}</label>
            <textarea
                cols="5"
                rows="5"
                class="form-control"
                v-model="form.incident_chronologic"
            ></textarea>
            <p class="text-danger" v-if="errors.incident_chronologic">
                {{ errors.incident_chronologic[0] }}
            </p>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h5 class="card-title">
                                    {{ $tc("File Upload", 2) }}
                                </h5>
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
                                <th width="50%">
                                    &nbsp;{{ $t("File Upload") }}
                                </th>
                                <th>{{ $t("Note") }}</th>
                                <th width="70px">{{ $t("action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in form.images"
                                :key="index"
                                class="justify-content-between align-items-center"
                            >
                                <td>
                                    <b-input-group>
                                        <b-input-group-prepend is-text>
                                            <span
                                                class="fas fa-file-upload"
                                            ></span>
                                        </b-input-group-prepend>
                                        <b-form-file
                                            id="form-image"
                                            @change="uploadFile($event, index)"
                                        ></b-form-file>
                                    </b-input-group>
                                    <div
                                        v-if="row.caption != null"
                                        class="float-right"
                                    >
                                        <label
                                            class="text-primary"
                                            v-if="!hasChange"
                                            ><span
                                                class="mdi mdi-information"
                                            ></span
                                            >&nbsp;Old File :
                                            {{ row.caption }}</label
                                        >
                                        <label class="text-success" v-else
                                            ><span
                                                class="mdi mdi-checkbox-marked-circle"
                                            ></span
                                            >&nbsp;{{
                                                $t("New File Is Uploaded!")
                                            }}</label
                                        >
                                    </div>
                                    <br />
                                </td>
                                <td>
                                    <textarea
                                        class="form-control form-control"
                                        :class="{
                                            'border-danger':
                                                errors[`${index}.note`]
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
            </div>
        </div>
        <div class="form-row">
            <div
                class="form-group col-12"
                :class="{ 'has-error': errors.lost_cost }"
            >
                <b-card class="col-md-12" bg-variant="light">
                    <label for>{{ $t("lost cost") }} </label>
                    <b-form-checkbox
                        class="ml-2"
                        inline
                        v-model="form.lostStatus"
                        :value="true"
                        :unchecked-value="false"
                        switch
                        >{{ $tc("Yes") }}</b-form-checkbox
                    >
                    <b-input-group
                        v-if="form.lostStatus == true"
                        prepend="Rp."
                        class="mb-2 mr-sm-2 mb-sm-0"
                    >
                        <b-input
                            class="form-control"
                            :class="{ 'border-danger': errors.lost_cost }"
                            :value="value"
                            v-imask="mask.amount"
                            @accept="onAccept"
                            @complete="onComplete"
                            placeholder="Enter number here"
                        ></b-input>
                    </b-input-group>
                    <p class="text-danger" v-if="errors.lost_cost">
                        {{ errors.lost_cost[0] }}
                    </p>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import selectExternalEmployee from "../../../../components/elements/custom/select-external-employee.vue";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelId"],
    data() {
        return {
            hasChange: false,
            imageList: [],
            fileList: [],
            listAllInEmployee: [],
            mask: {
                amount: {
                    mask: Number,
                    scale: 2,
                    signed: false,
                    thousandsSeparator: ",",
                    padFractionalZeros: false,
                    normalizeZeros: true,
                    radix: ",",
                    mapToRadix: ["."],
                    max: 1000000000
                }
            },
            value: "",
            lost_cost: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loaIncidentCategories();
        this.loadExternalEmployees();
        this.loadInventories();
        this.loadInterrogationReportTypes();
        this.changeValue();
        this.handleFileList();
        this.loadMachines();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("interrogationReport", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("inventory", {
            inventories: state => state.collectionList
        }),
        ...mapState("externalEmployee", {
            externalEmployees: state => state.collectionList
        }),
        ...mapState("incidentCategory", {
            incidentCategories: state => state.collectionList
        }),
        ...mapState("interrogationReportType", {
            interrogationReportTypes: state => state.collectionList
        }),
        ...mapState("machine", {
            machines: state => state.collectionList
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("interrogationReport", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("interrogationReportType", {
            loadInterrogationReportTypes: "loadList"
        }),
        ...mapActions("externalEmployee", {
            loadExternalEmployees: "loadList"
        }),
        ...mapActions("inventory", {
            loadInventories: "loadList"
        }),
        ...mapActions("incidentCategory", {
            loaIncidentCategories: "loadList"
        }),
        ...mapActions("machine", {
            loadMachines: "loadList"
        }),
        handleFileList() {
            console.log(this.form.images);
            if (this.modelId) {
                setTimeout(() => {
                    this.fileList = this.form.images.map(function(image) {
                        return {
                            id: image.id,
                            name: image.caption
                        };
                    });
                }, 1000);
            }
        },
        uploadFile(event, index) {
            console.log(event);
            this.hasChange = true;
            this.form.images[index].caption = event.target.files[0];
            console.log(this.form.images);
        },
        addFile() {
            // console.log(this.form.files);
            this.form.images.push({
                caption: null,
                note: null
            });
        },
        removeFile(index) {
            if (this.form.images.length > 1) {
                this.form.images.splice(index, 1);
            }
        },
        onAccept(e) {
            const maskRef = e.detail;
            this.value = maskRef.value;
            console.log("accept", maskRef.value);
        },
        changeValue() {
            console.log(this.form.images);
            this.value = this.form.lost_cost;
            if (this.form.date_of_incident == "") {
                const today = new Date();
                const yr = today.getFullYear();
                const mnt =
                    today.getMonth() + 1 <= 9
                        ? "0" + (today.getMonth() + 1)
                        : today.getMonth() + 1;
                const day =
                    today.getDate() <= 9
                        ? "0" + today.getDate()
                        : today.getDate();
                this.form.date_of_incident = yr + "-" + mnt + "-" + day;
                console.log("date", this.form.date_of_incident);
            }
        },
        addWitness() {
            this.form.actor_witness.push({
                id: null,
                employeeStatus: false,
                witnesses: null,
                external: null,
                description: null
            });
        },
        addSuspect() {
            this.form.actor_suspects.push({
                id: null,
                employeeStatus: false,
                suspects: null,
                external: null,
                description: null
            });
        },
        removeWitnesess(index) {
            if (this.form.actor_witness.length > 1) {
                this.form.actor_witness.splice(index, 1);
            }
        },
        removeSuspects(index) {
            if (this.form.actor_suspects.length > 1) {
                this.form.actor_suspects.splice(index, 1);
            }
        },
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        onComplete(e) {
            const maskRef = e.detail;
            this.form.lost_cost = maskRef.unmaskedValue;
        },
        selectedWitnesses(index, value) {
            this.form.actor_witness[index].witnesses = value;
            this.form.actor_witness[index].external = 0;
            console.log("Witnesses", this.form.actor_witness[index].witnesses);
        },
        selectedExternalWitnesses(index, value) {
            this.form.actor_witness[index].external = value;
            this.form.actor_witness[index].witnesses = 0;
            console.log(
                "Status",
                this.form.actor_witness[index].employeeStatus
            );
            console.log("Witnesses", this.form.actor_witness[index].witnesses);
        },
        selectedExternalSuspected(index, value) {
            this.form.actor_suspects[index].external = value;
            this.form.actor_suspects[index].suspects = 0;
            console.log("Suspected", this.form.actor_suspects[index].suspects);
        },
        selectedSuspected(index, value) {
            this.form.actor_suspects[index].suspects = value;
            this.form.actor_suspects[index].external = 0;
            console.log("Suspected", this.form.actor_suspects[index].suspects);
        },
        updateImageList(file) {
            // this.form.images = this.imageList;
            console.log(file.raw);
            this.form.images.push(file.raw);
        },
        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePreview(file) {
            console.log(file);
        },
        beforeRemove(file, fileList) {
            return this.$confirm(`Cancel the transfert of ${file.name} ?`);
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
        selectEmployee,
        selectExternalEmployee
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
