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
                    disabled
                ></v-select>
                <input type="hidden" v-model="form.id" />
                <p class="text-danger" v-if="errors.incident_category_id">
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
                    :class="{ 'border-danger': errors.date_of_incident }"
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
                                    form.interrogation_report_type_id == 5
                            "
                            v-model="form.typeable_id"
                            :options="inventories.data"
                            label="name"
                            :reduce="name => name.id"
                            disabled
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
                            disabled
                            :reduce="code => code.id"
                            :class="{ 'border-danger': errors.typeable_id }"
                        ></v-select>
                    </div>
                </div>
            </b-card>
        </div>
        <label for>{{ $tc("Lost Cost") }}</label>
        <div class="form-row">
            <div class="form-group col-md-9">
                <b-input-group prepend="Rp." class="mb-2 mr-sm-2 mb-sm-0">
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
                                            <th>&nbsp;{{ $t("File") }}</th>
                                            <th>{{ $t("Description") }}</th>
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
                                                <textarea
                                                    disabled
                                                    class="form-control"
                                                    v-model="row.note"
                                                ></textarea>
                                            </td>
                                            <td>
                                                <a
                                                    :href="
                                                        `/storage/images/interrogation-report/${row.image}`
                                                    "
                                                    target="_blank"
                                                    class="text-primary"
                                                    v-b-tooltip.hover
                                                    :title="$tc('download')"
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
        <div>
            <formActor :modelId="modelId"> </formActor>
        </div>
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
        <div v-if="!approvalStatus" class="form-row float-right">
            <b-button class="btn btn-secondary mr-2" @click="changeAccepted()"
                ><i class="fas fa-arrow-left mr-2"></i>
                {{ $t("cancel") }}</b-button
            >
            <button
                :disabled="rejectProcess"
                @click="rejectSubmit"
                class="btn btn-danger mr-2"
            >
                <b-spinner v-if="rejectProcess" small class="mr-2"></b-spinner>
                <i v-else class="fa fa-times mr-2"></i> {{ $t("Reject") }}
            </button>
            <button class="btn btn-success" @click="changeApproval()">
                <i class="fa fa-check mr-2"></i> {{ $t("Approve") }}
            </button>
        </div>
        <div v-if="approvalStatus == true && form.lost_cost == 0">
            <div>
                <form v-on:submit.prevent="submit">
                    <formNoLostCost :modelId="modelId"></formNoLostCost>
                </form>
            </div>
            <div class="form-row float-right">
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="changeAccepted()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    :disabled="loadingProcess"
                    class="btn btn-primary ml-2"
                    @click.prevent="submit"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-else class="fa fa-save mr-2"></i> {{ $t("Save") }}
                </button>
            </div>
        </div>
        <div v-if="approvalStatus == true && form.lost_cost > 0">
            <div>
                <form v-on:submit.prevent="submit">
                    <formLostCost :modelId="modelId"></formLostCost>
                </form>
            </div>
            <div class="form-row float-right">
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="changeAccepted()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    :disabled="loadingProcess"
                    class="btn btn-primary ml-2"
                    @click.prevent="submit"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-else class="fa fa-save mr-2"></i> {{ $t("Save") }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import vSelect from "vue-select";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import formActor from "./formActor.vue";
import formLostCost from "./formLostCost.vue";
import formNoLostCost from "./formNoLostCost.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelId"],
    data() {
        return {
            rejectProcess: false,
            loadingProcess: false,
            memorandumStatus: [],
            mask: {
                amount: {
                    mask: Number,
                    scale: 2,
                    signed: false,
                    thousandsSeparator: ",",
                    padFractionalZeros: false,
                    normalizeZeros: true,
                    radix: ",",
                    mapToRadix: ["."]
                }
            },
            value: "",
            NoLostCost: false,
            approvalStatus: false
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
        this.loadInventories();
        this.loadInterrogationReportTypes();
        this.loadMachines();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("approveIntterogation", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("incidentCategory", {
            incidentCategories: state => state.collectionList
        }),
        ...mapState("memorandumParameter", {
            memorandumParameters: state => state.collectionList
        }),
        ...mapState("inventory", {
            inventories: state => state.collectionList
        }),
        ...mapState("interrogationReportType", {
            interrogationReportTypes: state => state.collectionList
        }),
        ...mapState("machine", {
            machines: state => state.collectionList
        }),
        filterData() {
            var actor_type = this.form.interrogation_report_actors.filter(
                obj => obj.interrogation_report_actor_type_id == 2
            );
            return actor_type.length;
            console.log("data", actor_type.length);
        }
    },
    watch: {
        errors() {
            if (typeof this.errors == "object") {
                this.loadingProcess = false;
            }
        }
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("approveIntterogation", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("incidentCategory", {
            loaIncidentCategories: "loadList"
        }),
        ...mapActions("interrogationReportType", {
            loadInterrogationReportTypes: "loadList"
        }),
        ...mapActions("memorandumParameter", {
            loadmemorandumParameters: "loadList"
        }),
        ...mapActions("inventory", {
            loadInventories: "loadList"
        }),
        ...mapActions("machine", {
            loadMachines: "loadList"
        }),
        ...mapActions("approveIntterogation", [
            "load",
            "show",
            "store",
            "update"
        ]),
        onCompletePenalty(index, e) {
            console.log(e);
            console.log("index", index);
            const maskRef = e.detail;
            this.form.interrogation_report_actors[index].penalty =
                maskRef.unmaskedValue;
            console.log(
                "Penalty",
                this.form.interrogation_report_actors[index].penalty
            );
        },
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },

        getEndDate(index, date) {
            console.log(date);
            console.log("index", index);
            let currrentDate = new Date(date);
            let fixDate = new Date(
                currrentDate.setMonth(currrentDate.getMonth() + 6)
            );

            this.form.interrogation_report_actors[index].end_date =
                fixDate.getFullYear() +
                "-" +
                (fixDate.getMonth() + 1) +
                "-" +
                fixDate.getDate();
            console.log(
                "End Date",
                this.form.interrogation_report_actors[index].end_date
            );
        },
        isNumber(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            // console.log(value);
            // console.log("event", e);
            if (/^([1-9]|1[0-9]|2[0-4])$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        changeAccepted() {
            this.$emit("onAccepted", true);
        },
        onComplete(e) {
            const maskRef = e.detail;
            this.form.lost_cost = maskRef.unmaskedValue;
        },
        addIncidentPenalty() {
            this.form.incident_penalties.push({
                penalty: null,
                installment_count: null
            });
        },
        changeApproval() {
            this.approvalStatus = true;
            let that = this;
            for (let i = 0; i < this.form.suspects.length; i++) {
                this.form.suspects[i].penalty =
                    this.form.lost_cost / this.form.suspects.length;
            }
            this.form.interrogation_report_actors.forEach(function(
                item,
                index
            ) {
                that.form.interrogation_report_actors[index].penalty =
                    that.form.lost_cost / that.filterData;
                // that.form.interrogation_report_actors[index].statusMemorandum =
                // null
            });
        },
        changeNoLostCost() {
            this.NoLostCost = true;
        },
        submit() {
            this.loadingProcess = true;
            this.form.approve = "Accepted";
            this.update(this.modelId).then(() => {
                this.changeAccepted();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("memorandum"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },
        rejectSubmit() {
            this.rejectProcess = true;
            if (!this.modelId) {
                this.store().then(() => {
                    this.changeAccepted();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("memorandum"),
                            message: this.$tc("add", 2)
                        })
                    });
                });
            } else {
                this.form.approve = "Rejected";
                this.update(this.modelId).then(() => {
                    this.changeAccepted();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("memorandum"),
                            message: this.$tc("update", 2)
                        })
                    });
                });
            }
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
        formActor,
        formNoLostCost,
        formLostCost
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
