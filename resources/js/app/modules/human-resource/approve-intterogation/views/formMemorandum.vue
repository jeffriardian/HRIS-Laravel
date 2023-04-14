<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h5 class="card-title">
                                    {{ $tc("Memorandum History") }}
                                </h5>
                            </div>
                            <div class="col-3">
                                <button
                                    type="button"
                                    class="btn btn-info waves-effect waves-light float-right"
                                    v-b-toggle="`history`"
                                    variant="primary"
                                    v-b-tooltip.hover
                                    :title="$t('Detail Data')"
                                >
                                    {{ $t("Show") }}
                                </button>
                            </div>
                            <b-collapse :id="`history`" class="mt-2 col-md-12">
                                <b-card>
                                    <table
                                        class="table table-bordered table-sm table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th>
                                                    {{
                                                        $t(
                                                            "Memorandum Parameter"
                                                        )
                                                    }}
                                                </th>
                                                <th>
                                                    {{ $t("Start Date") }}
                                                </th>
                                                <th>
                                                    {{ $t("End Date") }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(row,
                                                index) in formMemorandum"
                                                :key="index"
                                                class="justify-content-between align-items-center"
                                            >
                                                <td>
                                                    <v-select
                                                        :options="
                                                            memorandumParameters.data
                                                        "
                                                        disabled
                                                        v-model="
                                                            formMemorandum[
                                                                index
                                                            ]
                                                                .memorandum_parameter_id
                                                        "
                                                        label="name"
                                                        :reduce="
                                                            name => name.id
                                                        "
                                                    ></v-select>
                                                </td>
                                                <td>
                                                    <date-picker
                                                        v-model="
                                                            formMemorandum[
                                                                index
                                                            ].start_date
                                                        "
                                                        format="YYYY-MM-DD"
                                                        value-type="format"
                                                        type="date"
                                                        disabled
                                                    />
                                                </td>
                                                <td>
                                                    <date-picker
                                                        v-model="
                                                            formMemorandum[
                                                                index
                                                            ].end_date
                                                        "
                                                        format="YYYY-MM-DD"
                                                        value-type="format"
                                                        type="date"
                                                        disabled
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </b-card>
                            </b-collapse>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>{{ $t("Memorandum Parameter") }}</label>
                <v-select
                    :options="memorandumParameters.data"
                    v-model="form.suspects[modelIndex].parameter_id"
                    label="name"
                    :reduce="name => name.id"
                ></v-select>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t("Start Date") }}</label>
                <date-picker
                    v-model="form.suspects[modelIndex].start_date"
                    @change="
                        getEndDate(
                            form.suspects[modelIndex].start_date,
                            modelIndex
                        )
                    "
                    format="YYYY-MM-DD"
                    value-type="format"
                    type="date"
                    :disabled-date="disabledAfterToday"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label>{{ $t("Description") }}</label>
                <textarea
                    class="form-control"
                    :class="{ 'border-danger': errors.description }"
                    v-model="form.suspects[modelIndex].memoDescription"
                ></textarea>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelIndex", "status"],
    data() {
        return {
            value: "",
            start_date: "",
            description: "",
            parameter: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
        this.showMemorandum({ id: this.modelIndex, status: this.status });
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("approveIntterogation", {
            form: state => state.form, //MENGAMBIL cSTATE DATA
            formMemorandum: state => state.formMemorandum
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
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
        ...mapActions("approveIntterogation", {
            showMemorandum: "showMemorandum"
        }),
        getEndDate(date, index) {
            let currrentDate = new Date(date);
            let fixDate = new Date(
                currrentDate.setMonth(currrentDate.getMonth() + 6)
            );

            this.form.suspects[index].end_date =
                fixDate.getFullYear() +
                "-" +
                (fixDate.getMonth() + 1) +
                "-" +
                fixDate.getDate();
        },
        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
    },
    components: {
        "v-select": vSelect,
        selectEmployee
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
