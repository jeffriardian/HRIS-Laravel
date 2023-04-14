<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab active>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Witness
                </template>
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
                                    <th>&nbsp;{{ $t("Witness") }}</th>
                                    <th>
                                        {{ $t("Description") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="justify-content-between align-items-center"
                                    v-for="(row,
                                    index) in form.interrogation_report_actors"
                                    :key="index"
                                >
                                    <td>
                                        <div>
                                            <input
                                                v-if="
                                                    row.interrogation_report_actor_type_id ==
                                                        1 &&
                                                        row.employee_id != 0
                                                "
                                                type="text"
                                                class="form-control"
                                                disabled
                                                v-model="row.employee.name"
                                            />
                                            <input
                                                v-else-if="
                                                    row.interrogation_report_actor_type_id ==
                                                        1 &&
                                                        row.employee_id == 0
                                                "
                                                type="text"
                                                class="form-control"
                                                disabled
                                                v-model="
                                                    row.external_employee.name
                                                "
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <textarea
                                            v-if="
                                                row.interrogation_report_actor_type_id ==
                                                    1
                                            "
                                            disabled
                                            class="form-control"
                                            v-model="row.description"
                                        ></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </b-tab>
            <b-tab>
                <template v-slot:title>
                    <span class="fa fa-users"></span>&nbsp;Suspects
                </template>
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
                                    <th>&nbsp;{{ $t("Suspect") }}</th>
                                    <th>
                                        {{ $t("Description") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="justify-content-between align-items-center"
                                    v-for="(row,
                                    index) in form.interrogation_report_actors"
                                    :key="index"
                                >
                                    <td>
                                        <div>
                                            <input
                                                v-if="
                                                    row.interrogation_report_actor_type_id ==
                                                        2 &&
                                                        row.employee_id != 0
                                                "
                                                type="text"
                                                class="form-control"
                                                disabled
                                                v-model="row.employee.name"
                                            />
                                            <input
                                                v-else-if="
                                                    row.interrogation_report_actor_type_id ==
                                                        2 &&
                                                        row.employee_id == 0
                                                "
                                                type="text"
                                                class="form-control"
                                                disabled
                                                v-model="
                                                    row.external_employee.name
                                                "
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <textarea
                                            v-if="
                                                row.interrogation_report_actor_type_id ==
                                                    2
                                            "
                                            disabled
                                            class="form-control"
                                            v-model="row.description"
                                        ></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </b-tab>
        </b-tabs>
    </div>
</template>

<script>
import moment from "moment";
import vSelect from "vue-select";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelId"],
    data() {
        return {
            rejectProcess: false,
            loadingProcess: false,
            value: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loadExternalEmployees();
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
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("approveIntterogation", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("externalEmployee", {
            loadExternalEmployees: "loadList"
        }),
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
        onComplete(e) {
            const maskRef = e.detail;
            this.form.penalty = maskRef.unmaskedValue;
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
        selectEmployee
    }
};
</script>
