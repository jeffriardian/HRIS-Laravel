<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h5>Suspect List</h5>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    {{ $t("Suspect") }}
                                </th>
                                <th class="text-center">
                                    {{ $t("Nominal") }}
                                </th>
                                <th class="text-center">
                                    {{ $t("Penalty") }}
                                </th>
                                <th class="text-center">
                                    {{ $t("Memorandum") }}
                                </th>
                                <th class="text-center">
                                    {{ $t("Action") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(row, index) in form.suspects"
                                :key="index"
                                class="justify-content-between align-items-center"
                            >
                                <td>
                                    <div v-if="row.suspects != 0">
                                        <v-select
                                            v-model="row.suspects"
                                            :options="employees.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            disabled
                                        ></v-select>
                                    </div>
                                    <div v-else>
                                        <v-select
                                            v-model="row.external"
                                            :options="externalEmployees.data"
                                            label="name"
                                            :reduce="name => name.id"
                                            disabled
                                        ></v-select>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <b-input-group prepend="Rp.">
                                        <b-form-input
                                            v-model="row.penalty"
                                            v-on:keyup="
                                                setTerminData(
                                                    row.penalty,
                                                    index
                                                )
                                            "
                                        ></b-form-input>
                                    </b-input-group>
                                </td>
                                <td class="text-center">
                                    <div
                                        v-if="row.statusPenalty"
                                        class="text-success"
                                    >
                                        <i
                                            class="mdi mdi-checkbox-marked-circle font-size-16"
                                        ></i>
                                        {{ $t("Already Set") }}
                                    </div>
                                    <div v-else class="text-danger">
                                        <i
                                            class="mdi mdi-close-circle font-size-16"
                                        ></i>
                                        {{ $t("Not Set") }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div
                                        v-if="row.statusMemorandum"
                                        class="text-success"
                                    >
                                        <i
                                            class="mdi mdi-checkbox-marked-circle font-size-16"
                                        ></i>
                                        {{ $t("Already Set") }}
                                    </div>
                                    <div v-else class="text-danger">
                                        <i
                                            class="mdi mdi-close-circle font-size-16"
                                        ></i>
                                        {{ $t("Not Set") }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a
                                        href="javascript:void(0)"
                                        @click="
                                            checkPenalty(
                                                row.suspects,
                                                row.external,
                                                index
                                            )
                                        "
                                        class="text-success"
                                        :title="$tc('Set Data Penalty')"
                                        v-b-tooltip.hover
                                        ><i
                                            class="mdi mdi-coin font-size-16"
                                        ></i
                                    ></a>
                                    <b-modal
                                        hide-header-close
                                        no-close-on-backdrop
                                        size="xl"
                                        :id="`form-penalty-${index}`"
                                        :title="
                                            $t('form module', {
                                                module: $tc('Penalty')
                                            })
                                        "
                                        ref="modal"
                                    >
                                        <div v-if="row.suspects == 0">
                                            <formPenalty
                                                :modelIndex="index"
                                                :status="'External'"
                                            ></formPenalty>
                                        </div>
                                        <div v-else>
                                            <formPenalty
                                                :modelIndex="index"
                                                :status="'Internal'"
                                            ></formPenalty>
                                        </div>
                                        <template v-slot:modal-footer>
                                            <b-button
                                                class="btn btn-danger ml-2"
                                                @click="hideModalPenalty(index)"
                                            >
                                                <i
                                                    class="fas fa-times-circle mr-2"
                                                ></i>
                                                {{ $t("cancel") }}
                                            </b-button>
                                            <button
                                                :disabled="
                                                    loadingProcess ||
                                                        row.installment_count ==
                                                            null ||
                                                        row.payment == null
                                                "
                                                class="btn btn-primary"
                                                @click="submitPenalty(index)"
                                            >
                                                <b-spinner
                                                    v-if="loadingProcess"
                                                    small
                                                    class="mr-2"
                                                ></b-spinner>
                                                <i
                                                    v-else
                                                    class="far fa-save mr-2"
                                                ></i>
                                                {{ $t("save") }}
                                            </button>
                                        </template>
                                    </b-modal>
                                    <a
                                        href="javascript:void(0)"
                                        @click="
                                            check(
                                                row.suspects,
                                                row.external,
                                                index
                                            )
                                        "
                                        class="text-primary"
                                        :title="$tc('Set Data Memorandum')"
                                        v-b-tooltip.hover
                                        ><i
                                            class="mdi mdi-clipboard-alert font-size-16"
                                        ></i
                                    ></a>
                                    <b-modal
                                        hide-header-close
                                        no-close-on-backdrop
                                        size="lg"
                                        :id="`form-memorandum-${index}`"
                                        :title="
                                            $t('form module', {
                                                module: $tc('Memorandum')
                                            })
                                        "
                                        ref="modal"
                                    >
                                        <div v-if="row.suspects == 0">
                                            <formMemorandum
                                                :modelIndex="index"
                                                :status="'External'"
                                            ></formMemorandum>
                                        </div>
                                        <div v-else>
                                            <formMemorandum
                                                :modelIndex="index"
                                                :status="'Internal'"
                                            ></formMemorandum>
                                        </div>
                                        <template v-slot:modal-footer>
                                            <b-button
                                                class="btn btn-danger ml-2"
                                                @click="
                                                    hideModalMemorandum(index)
                                                "
                                            >
                                                <i
                                                    class="fas fa-times-circle mr-2"
                                                ></i>
                                                {{ $t("cancel") }}
                                            </b-button>
                                            <button
                                                :disabled="
                                                    loadingProcess ||
                                                        row.end_date == null ||
                                                        row.start_date ==
                                                            null ||
                                                        row.parameter_id == null
                                                "
                                                class="btn btn-primary"
                                                @click="submitMemorandum(index)"
                                            >
                                                <b-spinner
                                                    v-if="loadingProcess"
                                                    small
                                                    class="mr-2"
                                                ></b-spinner>
                                                <i
                                                    v-else
                                                    class="far fa-save mr-2"
                                                ></i>
                                                {{ $t("save") }}
                                            </button>
                                        </template>
                                    </b-modal>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import formMemorandum from "./formMemorandum.vue";
import formPenalty from "./formPenalty.vue";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelId"],
    data() {
        return {
            memorandumStatus: [],
            loadingProcess: false,
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
            value: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loadExternalEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
        // this.changeValue();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("approveIntterogation", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("externalEmployee", {
            externalEmployees: state => state.collectionList
        }),
        ...mapState("incidentCategory", {
            incidentCategories: state => state.collectionList
        }),
        ...mapState("memorandumParameter", {
            memorandumParameters: state => state.collectionList
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
        ...mapActions("externalEmployee", {
            loadExternalEmployees: "loadList"
        }),
        ...mapActions("incidentCategory", {
            loaIncidentCategories: "loadList"
        }),
        ...mapActions("memorandumParameter", {
            loadmemorandumParameters: "loadList"
        }),
        ...mapActions("approveIntterogation", [
            "showMemorandum",
            "showPenalty"
        ]),
        setTerminData(data, index) {
            var i = 0;
            var total_termin = this.form.suspects.length;
            var total_penalty = this.form.lost_cost / total_termin;
            var total_nominal = 0;

            if (data > this.form.lost_cost || data == isNaN(data)) {
                data = parseFloat(total_penalty);
            } else {
                data = parseFloat(data);
            }

            this.form.suspects[index].penalty = data;

            for (i = 0; i <= index; i++) {
                total_nominal =
                    total_nominal + parseFloat(this.form.suspects[i].penalty);
            }

            for (i = index + 1; i < total_termin; i++) {
                this.form.suspects[i].penalty =
                    (this.form.lost_cost - total_nominal) /
                    (total_termin - (index + 1));
            }
        },
        check(internalId, externalId, index) {
            this.form.suspects[index].statusMemorandum = true;
            if (internalId == 0) {
                let id = externalId;
                let status = "External";
                this.showMemorandum({ id: id, status: status });
                this.$bvModal.show(`form-memorandum-${index}`);
            } else {
                let id = internalId;
                let status = "Internal";
                this.showMemorandum({ id: id, status: status });
                this.$bvModal.show(`form-memorandum-${index}`);
            }
        },
        checkPenalty(internalId, externalId, index) {
            this.form.suspects[index].statusPenalty = true;
            if (internalId == 0) {
                let id = externalId;
                let status = "External";
                this.showPenalty({ id: id, status: status });
                this.$bvModal.show(`form-penalty-${index}`);
            } else {
                let id = internalId;
                let status = "Internal";
                this.showPenalty({ id: id, status: status });
                this.$bvModal.show(`form-penalty-${index}`);
            }
        },
        hideModalPenalty(index) {
            this.loadingProcess = false;
            this.$confirm(
                this.$t(
                    "Penalty Confirmation? Your Memorandum Data Will be cancelled by system",
                    {
                        // item: item.employee,
                        module: this.$tc("Penalty")
                    }
                ),
                this.$t("Confirmation"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    this.form.suspects[index].statusPenalty = false;
                    this.$bvModal.hide(`form-penalty-${index}`);
                    this.$message({
                        type: "error",
                        message: this.$t("penalty successfully cancelled")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("penalty canceled")
                    });
                });
        },
        hideModalMemorandum(index) {
            this.loadingProcess = false;
            this.$confirm(
                this.$t(
                    "Memorandum Confirmation? Your Memorandum Data Will be cancelled by system",
                    {
                        // item: item.employee,
                        module: this.$tc("Memorandum")
                    }
                ),
                this.$t("Confirmation"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    this.form.suspects[index].statusMemorandum = false;
                    this.$bvModal.hide(`form-memorandum-${index}`);
                    this.$message({
                        type: "error",
                        message: this.$t("memorandum successfully cancelled")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("memorandum canceled")
                    });
                });
        },
        hideModal(index) {
            this.$bvModal.hide(`form-memorandum-${index}`);
            this.loadingProcess = false;
        },
        submitMemorandum(index) {
            this.hideModal(index);
            this.$message({
                type: "success",
                message: this.$t("present perfect", {
                    object: this.$tc("memorandum"),
                    message: this.$tc("add", 2)
                })
            });
        },
        hideSubmitPenalty(index) {
            this.$bvModal.hide(`form-penalty-${index}`);
            this.loadingProcess = false;
        },
        submitPenalty(index) {
            this.hideSubmitPenalty(index);
            this.$message({
                type: "success",
                message: this.$t("present perfect", {
                    object: this.$tc("penalty"),
                    message: this.$tc("add", 2)
                })
            });
        },
        onCompletePenalty(index, e) {
            const maskRef = e.detail;
            this.form.suspects[index].penalty = maskRef.unmaskedValue;
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
        formMemorandum,
        formPenalty
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
