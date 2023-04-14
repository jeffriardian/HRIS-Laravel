<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc("Employee Name") }}</label>
            <select-employee
                @selectedEmployee="selectedEmployees"
                :data="employees.data"
                :singleSelected="true"
                v-model="form.employee_id"
            ></select-employee>
            <p class="text-danger" v-if="errors.employee_id">
                {{ errors.employee_id[0] }}
            </p>
        </div>
        <div
            class="form-group"
            :class="{ 'has-error': errors.memorandum_parameter_id }"
        >
            <label for="">{{ $tc("Memorandum  Parameter") }}</label>
            <v-select
                v-model="form.memorandum_parameter_id"
                :options="memorandumParameters.data"
                label="name"
                :reduce="name => name.id"
                :class="{ 'border-danger': errors.memorandum_parameter_id }"
            ></v-select>
            <p class="text-danger" v-if="errors.memorandum_parameter_id">
                {{ errors.memorandum_parameter_id[0] }}
            </p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_date }">
            <label for="">{{ $tc("Start Date") }}</label>
            <!-- buat datepicker disini -->
            <DatePicker
                :class="{
                    'border-danger': errors.start_date
                }"
                value-type="format"
                format="YYYY-MM-DD"
                @change="getEndDate(form.start_date)"
                v-model="form.start_date"
                :disabled-date="disabledAfterToday"
            />
            <p class="text-danger" v-if="errors.start_date">
                {{ errors.start_date[0] }}
            </p>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t("Description") }}</label>
            <textarea
                class="form-control"
                :class="{ 'border-danger': errors.description }"
                v-model="form.description"
            ></textarea>
            <p class="text-danger" v-if="errors.description">
                {{ errors.description[0] }}
            </p>
        </div>

        <div class="form-group">
            <b-form-checkbox
                v-model="form.is_active"
                value="1"
                unchecked-value="0"
            >
                {{ $t("status") }}
            </b-form-checkbox>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import { Datetime } from "vue-datetime";
import moment from "moment";
import DatePicker from "vue2-datepicker";
import "vue-datetime/dist/vue-datetime.css";
import "vue-datetime/dist/vue-datetime.js";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";

export default {
    props: ["modelId"],
    data() {
        return {
            listAllInEmployee: []
            // value: ""
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("memorandum", {
            form: state => state.form //MENGAMBIL STATE DATA
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
        ...mapMutations("memorandum", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions("employee", { loadEmployees: "loadList" }),
        ...mapActions("memorandumParameter", {
            loadmemorandumParameters: "loadList"
        }),
        getEndDate(date) {
            console.log(date);
            let currrentDate = new Date(date);
            this.form.end_date = new Date(
                currrentDate.setMonth(currrentDate.getMonth() + 6)
            );
            console.log("End Date", this.form.end_date);
        },
        selectedEmployees(value) {
            console.log("id employee", value);
            this.form.employee_id = value;
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
        this.CLEAR_FORM();
    },
    components: {
        "v-select": vSelect,
        datetime: Datetime,
        DatePicker,
        selectEmployee
    }
};
</script>
