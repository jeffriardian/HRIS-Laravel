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
                                        v-if="!row.statusMemorandum"
                                        href="javascript:void(0)"
                                        @click="
                                            check(
                                                row.suspects,
                                                row.external,
                                                index
                                            )
                                        "
                                        class="text-primary"
                                        :title="$tc('Set Data')"
                                        v-b-tooltip.hover
                                        ><i
                                            class="mdi mdi-settings font-size-16"
                                        ></i
                                    ></a>
                                    <a
                                        v-if="row.statusMemorandum"
                                        href="javascript:void(0)"
                                        @click="deleteMemo(index, row)"
                                        class="text-danger"
                                        :title="$tc('Clear Data')"
                                        v-b-tooltip.hover
                                        ><i
                                            class="mdi mdi-delete font-size-16"
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
                                                :disabled="loadingProcess"
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
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelId"],
    data() {
        return {
            value: "",
            loadingProcess: false
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
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
        })
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
        ...mapActions("memorandumParameter", {
            loadmemorandumParameters: "loadList"
        }),
        ...mapActions("approveIntterogation", ["showMemorandum"]),
        check(internalId, externalId, index) {
            this.form.suspects[index].statusMemorandum = true;
            console.log("Internal", internalId);
            console.log("External", externalId);
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
        deleteMemo(index, row) {
            this.loadingProcess = false;
            this.$confirm(
                this.$t(
                    "Memorandum Confirmation? Your Memorandum Data Will be deleted by system",
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
                    row.statusMemorandum = false;
                    row.parameter_id = null;
                    row.start_date = null;
                    row.end_date = null;
                    row.memoDescription = null;
                    this.$message({
                        type: "error",
                        message: this.$t("memorandum successfully deleted")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("memorandum canceled")
                    });
                });
        },
        hideModalMemorandum(index) {
            this.loadingProcess = false;
            console.log("babi", index);
            this.$confirm(
                this.$t(
                    "Memorandum Confirmation? Your Memorandum Data Will be cleaned by system",
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
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
    },
    components: {
        "v-select": vSelect,
        selectEmployee,
        formMemorandum
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
