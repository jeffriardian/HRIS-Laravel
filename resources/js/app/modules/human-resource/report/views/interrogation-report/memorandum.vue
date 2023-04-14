<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab active>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Memorandum
                </template>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="card m-b-30">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-9"></div>
                                    <div class="col-3"></div>
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
                                            <tr v-if="formMemo.length == 0">
                                                <td
                                                    colspan="3"
                                                    class="text-center"
                                                >
                                                    <h5>
                                                        {{
                                                            $t(
                                                                "No History Recorded"
                                                            )
                                                        }}
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr
                                                v-else
                                                v-for="(row, index) in formMemo"
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
                                                            formMemo[index]
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
                                                            formMemo[index]
                                                                .start_date
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
                                                            formMemo[index]
                                                                .end_date
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </b-tab>
            <b-tab>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Incident Penalty
                </template>
                <formPenalty></formPenalty>
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import formPenalty from "./incident-penalty";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    data() {
        return {
            value: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("reportInterrogation", {
            formMemo: state => state.formMemo
        }),
        ...mapState("employee", {
            employees: state => state.collectionList
        }),
        ...mapState("memorandumParameter", {
            memorandumParameters: state => state.collectionList
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("reportInterrogation", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("incidentCategory", {
            loaIncidentCategories: "loadList"
        }),
        ...mapActions("memorandumParameter", {
            loadmemorandumParameters: "loadList"
        })
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
    },
    components: {
        "v-select": vSelect,
        formPenalty
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
