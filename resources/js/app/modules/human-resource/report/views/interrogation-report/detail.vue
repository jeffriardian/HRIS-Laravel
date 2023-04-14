<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab active>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Interrogation
                </template>
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
                            :class="{
                                'border-danger': errors.incident_category_id
                            }"
                            disabled
                        ></v-select>
                        <input type="hidden" v-model="form.id" />
                        <p
                            class="text-danger"
                            v-if="errors.incident_category_id"
                        >
                            {{ errors.incident_category_id[0] }}
                        </p>
                    </div>
                    <div
                        class="form-group col-6"
                        :class="{ 'has-error': errors.date_of_incident }"
                    >
                        <label for>{{ $tc("date of incident") }}</label>
                        <date-picker
                            format="YYYY-MM-DD"
                            value-type="format"
                            type="date"
                            :class="{
                                'border-danger': errors.date_of_incident
                            }"
                            v-model="form.date_of_incident"
                            disabled
                        />
                        <p class="text-danger" v-if="errors.date_of_incident">
                            {{ errors.date_of_incident[0] }}
                        </p>
                    </div>
                </div>
                <div class="form-row">
                    <b-card class="col-md-12" bg-variant="light">
                        <label for>{{ $tc("Property Damage") }}</label>
                        <b-form-checkbox
                            class="ml-2"
                            inline
                            v-model="form.orderStatus"
                            :value="true"
                            :unchecked-value="false"
                            switch
                            disabled
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
                                    disabled
                                ></v-select>
                            </div>
                            <div class="col-md-9">
                                <v-select
                                    v-if="
                                        form.orderStatus &&
                                            form.interrogation_report_type_id ==
                                                5
                                    "
                                    v-model="form.typeable_id"
                                    :options="inventories.data"
                                    label="name"
                                    :reduce="name => name.id"
                                    disabled
                                    :class="{
                                        'border-danger': errors.typeable_id
                                    }"
                                ></v-select>
                                <v-select
                                    v-if="
                                        form.orderStatus &&
                                            form.interrogation_report_type_id ==
                                                6
                                    "
                                    v-model="form.typeable_id"
                                    :options="machines.data"
                                    label="code"
                                    disabled
                                    :reduce="code => code.id"
                                    :class="{
                                        'border-danger': errors.typeable_id
                                    }"
                                ></v-select>
                            </div>
                        </div>
                    </b-card>
                </div>
                <label for>{{ $tc("Lost Cost") }}</label>
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <b-input-group
                            prepend="Rp."
                            class="mb-2 mr-sm-2 mb-sm-0"
                        >
                            <b-input
                                class="form-control"
                                :class="{ 'border-danger': errors.lost_cost }"
                                :value="form.lost_cost"
                                disabled
                                v-imask="mask.amount"
                                @complete="onComplete"
                            ></b-input>
                        </b-input-group>
                    </div>
                    <div class="form-group col-md-3">
                        <button
                            type="button"
                            class="btn btn-info col-md-12"
                            v-b-toggle="`detail-data-file`"
                            variant="info"
                            v-b-tooltip.hover
                            :title="$t('Detail')"
                        >
                            <i class="fa fa-file mr-2"></i>{{ $t("File List") }}
                        </button>
                    </div>
                </div>
                <div class="form-row">
                    <b-collapse :id="`detail-data-file`" class="mt-2 col-md-12">
                        <b-card>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <table
                                            class="table table-bordered table-sm table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>
                                                        &nbsp;{{ $t("File") }}
                                                    </th>
                                                    <th width="70px">
                                                        {{ $t("Action") }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class="justify-content-between align-items-center"
                                                    v-for="(row,
                                                    index) in form.interrogation_report_images"
                                                    :key="index"
                                                >
                                                    <td>
                                                        <input
                                                            type="text"
                                                            disabled
                                                            class="form-control"
                                                            v-model="row.image"
                                                        />
                                                    </td>
                                                    <td>
                                                        <a
                                                            :href="
                                                                `/storage/images/interrogation-report/${row.image}`
                                                            "
                                                            target="_blank"
                                                            class="text-primary"
                                                            v-b-tooltip.hover
                                                            :title="
                                                                $tc('download')
                                                            "
                                                        >
                                                            <i
                                                                class="mdi mdi-arrow-down-bold-circle-outline font-size-16"
                                                            ></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </b-card>
                    </b-collapse>
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
                                            <div class="col-3"></div>
                                        </div>
                                    </div>
                                    <table
                                        class="table table-bordered table-sm table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th>
                                                    &nbsp;{{ $t("Status") }}
                                                </th>
                                                <th>{{ $t("Witness") }}</th>
                                                <th>{{ $t("Description") }}</th>
                                                <th>{{ $t("History") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(row,
                                                index) in form.interrogation_report_actors"
                                                :key="index"
                                                v-if="
                                                    row.interrogation_report_actor_type_id ==
                                                        1
                                                "
                                                class="justify-content-between align-items-center"
                                            >
                                                <td class="text-center">
                                                    <div
                                                        v-if="
                                                            row.employee_id != 0
                                                        "
                                                    >
                                                        <span
                                                            class="badge badge-pill badge-soft-primary font-size-12"
                                                        >
                                                            {{ $t("Internal") }}
                                                        </span>
                                                    </div>
                                                    <div v-else>
                                                        <span
                                                            class="badge badge-pill badge-soft-danger font-size-12"
                                                        >
                                                            {{ $t("External") }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        v-if="
                                                            row.employee_id != 0
                                                        "
                                                    >
                                                        <input
                                                            type="text"
                                                            class="form-control mb-2"
                                                            v-model="
                                                                row.employee
                                                                    .name
                                                            "
                                                            disabled
                                                        />
                                                    </div>
                                                    <div v-else>
                                                        <input
                                                            type="text"
                                                            class="form-control mb-2"
                                                            v-model="
                                                                row
                                                                    .external_employee
                                                                    .name
                                                            "
                                                            disabled
                                                        />
                                                    </div>
                                                    <br />
                                                </td>
                                                <td>
                                                    <textarea
                                                        class="form-control"
                                                        disabled
                                                        v-model="
                                                            row.description
                                                        "
                                                    ></textarea>
                                                </td>
                                                <td class="text-center">
                                                    <div
                                                        v-if="
                                                            row.employee_id != 0
                                                        "
                                                    >
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="
                                                                view(
                                                                    row.employee_id,
                                                                    'Internal'
                                                                )
                                                            "
                                                            class="text-primary"
                                                            v-b-tooltip.hover
                                                            :title="
                                                                $tc(
                                                                    'show history'
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="mdi mdi-eye font-size-16"
                                                            ></i>
                                                        </a>
                                                    </div>
                                                    <div v-else>
                                                        <a
                                                            href="javascript:void(0)"
                                                            @click="
                                                                view(
                                                                    row.external_employee_id,
                                                                    'External'
                                                                )
                                                            "
                                                            class="text-primary"
                                                            v-b-tooltip.hover
                                                            :title="
                                                                $tc(
                                                                    'show history'
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                class="mdi mdi-eye font-size-16"
                                                            ></i>
                                                        </a>
                                                    </div>
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
                            <span class="fa fa-users"></span>&nbsp;Suspect
                        </template>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p
                                    class="text-danger"
                                    v-if="errors.suspect_actor"
                                >
                                    {{ errors.suspect_actor[0] }}
                                </p>
                                <div class="card m-b-30">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-9"></div>
                                            <div class="col-3"></div>
                                        </div>
                                    </div>
                                </div>
                                <table
                                    class="table table-bordered table-sm table-hover"
                                >
                                    <thead>
                                        <tr>
                                            <th>&nbsp;{{ $t("Status") }}</th>
                                            <th>{{ $t("Suspects") }}</th>
                                            <th>{{ $t("Description") }}</th>
                                            <th>{{ $t("History") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(row,
                                            index) in form.interrogation_report_actors"
                                            :key="index"
                                            v-if="
                                                row.interrogation_report_actor_type_id ==
                                                    2
                                            "
                                            class="justify-content-between align-items-center"
                                        >
                                            <td class="text-center">
                                                <div
                                                    v-if="row.employee_id != 0"
                                                >
                                                    <span
                                                        class="badge badge-pill badge-soft-primary font-size-12"
                                                    >
                                                        {{ $t("Internal") }}
                                                    </span>
                                                </div>
                                                <div v-else>
                                                    <span
                                                        class="badge badge-pill badge-soft-danger font-size-12"
                                                    >
                                                        {{ $t("External") }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    v-if="row.employee_id != 0"
                                                >
                                                    <input
                                                        type="text"
                                                        class="form-control mb-2"
                                                        v-model="
                                                            row.employee.name
                                                        "
                                                        disabled
                                                    />
                                                </div>
                                                <div v-else>
                                                    <input
                                                        type="text"
                                                        class="form-control mb-2"
                                                        v-model="
                                                            row
                                                                .external_employee
                                                                .name
                                                        "
                                                        disabled
                                                    />
                                                </div>
                                                <br />
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control"
                                                    disabled
                                                    v-model="row.description"
                                                ></textarea>
                                            </td>
                                            <td class="text-center">
                                                <div
                                                    v-if="row.employee_id != 0"
                                                >
                                                    <a
                                                        href="javascript:void(0)"
                                                        @click="
                                                            view(
                                                                row.employee_id,
                                                                'Internal'
                                                            )
                                                        "
                                                        class="text-primary"
                                                        v-b-tooltip.hover
                                                        :title="
                                                            $tc('show history')
                                                        "
                                                    >
                                                        <i
                                                            class="mdi mdi-eye font-size-16"
                                                        ></i>
                                                    </a>
                                                </div>
                                                <div v-else>
                                                    <a
                                                        href="javascript:void(0)"
                                                        @click="
                                                            view(
                                                                row.external_employee_id,
                                                                'External'
                                                            )
                                                        "
                                                        class="text-primary"
                                                        v-b-tooltip.hover
                                                        :title="
                                                            $tc('show history')
                                                        "
                                                    >
                                                        <i
                                                            class="mdi mdi-eye font-size-16"
                                                        ></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
                <div class="form-row">
                    <b-card class="col-md-12" bg-variant="light">
                        <div class="form-group col-md-12">
                            <label for>{{ $t("Incident Chronology") }}</label>
                            <textarea
                                class="form-control"
                                v-model="form.incident_chronologic"
                                disabled
                            ></textarea>
                        </div>
                    </b-card>
                </div>
            </b-tab>
        </b-tabs>
        <b-modal
            id="form-memorandum"
            size="lg"
            :title="$t('History')"
            ref="modal"
        >
            <formMemo></formMemo>
        </b-modal>
    </div>
</template>

<script>
import moment from "moment";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import selectEmployee from "../../../../../components/elements/custom/select-employee.vue";
import { IMaskDirective } from "vue-imask";
import formMemo from "./memorandum.vue";

export default {
    props: ["modelId"],
    data() {
        return {
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
        this.changeValue();
        this.loadInterrogationReportTypes();
        this.loadInventories();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("reportInterrogation", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("incidentCategory", {
            incidentCategories: state => state.collectionList
        }),
        ...mapState("interrogationReportType", {
            interrogationReportTypes: state => state.collectionList
        }),
        ...mapState("inventory", {
            inventories: state => state.collectionList
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("reportInterrogation", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("incidentCategory", {
            loaIncidentCategories: "loadList"
        }),
        ...mapActions("interrogationReportType", {
            loadInterrogationReportTypes: "loadList"
        }),
        ...mapActions("inventory", {
            loadInventories: "loadList"
        }),
        ...mapActions("reportInterrogation", ["getMemo"]),
        view(id, status) {
            this.getMemo({ id: id, status: status });
            this.$bvModal.show("form-memorandum");
        },
        onAccept(e) {
            const maskRef = e.detail;
            this.value = maskRef.value;
            console.log("accept", maskRef.value);
        },
        changeValue() {
            console.log(this.form.images);
            this.value = this.form.lost_cost;
            if (this.value > 0) {
                this.form.lostStatus = true;
            }
            if (this.form.date_of_incident == "") {
                // /console.log("tidak null");
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
            // return this.value;
        },
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        onComplete(e) {
            const maskRef = e.detail;
            this.form.lost_cost = maskRef.unmaskedValue;
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
        formMemo
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
