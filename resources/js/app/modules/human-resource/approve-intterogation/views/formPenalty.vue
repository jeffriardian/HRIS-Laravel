<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="card m-b-30">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h5 class="card-title">
                                    {{ $tc("Penalty History") }}
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
                                                    {{ $t("Penalty") }}
                                                </th>
                                                <th>
                                                    {{
                                                        $t("Installment Count")
                                                    }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(row,
                                                index) in formPenalty"
                                                :key="index"
                                                class="justify-content-between align-items-center"
                                            >
                                                <td>
                                                    <b-input-group
                                                        prepend="Rp."
                                                    >
                                                        <b-form-input
                                                            :value="row.penalty"
                                                            v-imask="
                                                                mask.amount
                                                            "
                                                            @complete="
                                                                onCompletePenalty(
                                                                    index,
                                                                    ...arguments
                                                                )
                                                            "
                                                        ></b-form-input>
                                                    </b-input-group>
                                                </td>
                                                <td>
                                                    <b-input-group
                                                        append="x Installment"
                                                    >
                                                        <b-form-input
                                                            v-model="
                                                                row.installment_count
                                                            "
                                                        ></b-form-input>
                                                    </b-input-group>
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
                <label>{{ $t("Penalty") }}</label>
                <b-input-group prepend="Rp.">
                    <b-form-input
                        :value="form.suspects[modelIndex].penalty"
                        v-imask="mask.amount"
                        disabled
                    ></b-form-input>
                </b-input-group>
            </div>
            <div class="form-group col-md-6">
                <b-form-group :label="$t('Payment')">
                    <b-form-radio-group
                        v-model="form.suspects[modelIndex].payment"
                        :options="paymentOptions"
                    ></b-form-radio-group>
                </b-form-group>
            </div>
        </div>
        <div v-if="form.suspects[modelIndex].payment == 'Installment'">
            <b-card class="col-md-12" bg-variant="light">
                <div class="form-row">
                    <div class="col-md-2">
                        <label>{{ $t("Installment Count") }}</label>
                        <input
                            type="text"
                            class="form-control"
                            v-int
                            v-model="
                                form.suspects[modelIndex].installment_count
                            "
                        />
                    </div>
                    <div class="col-md-2">
                        <label>{{ $t("Type of Installment") }}</label>
                        <b-form-checkbox
                            v-model="form.suspects[modelIndex].flatStatus"
                            :value="true"
                            @change="clearData"
                            :unchecked-value="false"
                            >{{ $t("Flat") }}</b-form-checkbox
                        >
                    </div>
                    <div
                        class="col-md-2"
                        v-if="form.suspects[modelIndex].flatStatus"
                    >
                        <label>{{ $t("Period of Time") }}</label>
                        <input
                            type="text"
                            v-int
                            class="form-control"
                            v-model="form.suspects[modelIndex].period"
                        />
                    </div>
                    <div
                        class="col-md-2"
                        v-if="form.suspects[modelIndex].flatStatus"
                    >
                        <label>{{ $t("Type of Period") }}</label>
                        <v-select
                            v-model="form.suspects[modelIndex].periodType"
                            :options="periodTypeOptions"
                            label="text"
                            :reduce="text => text.value"
                        ></v-select>
                    </div>
                    <div
                        class="col-md-3"
                        v-if="form.suspects[modelIndex].flatStatus"
                    >
                        <label>{{ $t("Early Payment") }}</label>
                        <date-picker
                            v-model="form.suspects[modelIndex].early_payment"
                            format="YYYY-MM-DD"
                            value-type="format"
                            type="date"
                            :disabled-date="disabledBeforeToday"
                        />
                    </div>
                    <div class="col-md-1">
                        <label>{{ $t("Action") }}</label>
                        <b-button
                            :disabled="
                                form.suspects[modelIndex].installment_count ==
                                    null ||
                                    form.suspects[modelIndex].penalty <= 0
                            "
                            v-if="
                                form.suspects[modelIndex].periodType == 'Month'
                            "
                            @click="
                                getDataPenaltyMonth(
                                    form.suspects[modelIndex].early_payment
                                )
                            "
                            class="col-md-12 btn btn-info"
                        >
                            OK
                        </b-button>
                        <b-button
                            :disabled="
                                form.suspects[modelIndex].installment_count ==
                                    null ||
                                    form.suspects[modelIndex].penalty <= 0
                            "
                            v-else-if="
                                form.suspects[modelIndex].flatStatus == false
                            "
                            @click="getDataPenalty"
                            class="col-md-12 btn btn-info"
                        >
                            OK
                        </b-button>
                        <b-button
                            :disabled="
                                form.suspects[modelIndex].installment_count ==
                                    null ||
                                    form.suspects[modelIndex].penalty <= 0
                            "
                            v-else
                            @click="
                                getDataPenaltyWeek(
                                    form.suspects[modelIndex].early_payment
                                )
                            "
                            class="col-md-12 btn btn-info"
                        >
                            OK
                        </b-button>
                    </div>
                </div>
            </b-card>
            <div class="form-row">
                <label>{{ $t("Detail Penalty") }}</label>
                <b-card class="col-md-12" bg-variant="light">
                    <p v-if="!okStatus" class="text-center text-black">
                        The list of installment is not yet available, please
                        click 'OK' first
                    </p>
                    <div v-else>
                        <b-card class="col-md-12">
                            <table
                                class="table table-bordered table-sm table-hover"
                            >
                                <thead>
                                    <tr>
                                        <th>
                                            {{ $t("Amount") }}
                                        </th>
                                        <th>
                                            {{ $t("Percentage (%)") }}
                                        </th>
                                        <th>
                                            {{ $t("Due Date") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    v-if="form.suspects[modelIndex].flatStatus"
                                >
                                    <tr
                                        v-for="(row, index) in form.suspects[
                                            modelIndex
                                        ].penalties"
                                        :key="index"
                                        class="justify-content-between align-items-center"
                                    >
                                        <td>
                                            Rp.
                                            {{ numberWithCommas(row.amount) }}
                                        </td>
                                        <td>{{ row.percentage }}%</td>
                                        <td>{{ row.due_date }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr
                                        v-for="(row, index) in form.suspects[
                                            modelIndex
                                        ].penalties"
                                        :key="index"
                                        class="justify-content-between align-items-center"
                                    >
                                        <td>
                                            <b-input-group prepend="Rp.">
                                                <b-form-input
                                                    v-model="row.amount"
                                                    v-on:keyup="
                                                        setTerminData(
                                                            row.amount,
                                                            'nominal',
                                                            index
                                                        )
                                                    "
                                                ></b-form-input>
                                            </b-input-group>
                                        </td>
                                        <td>
                                            <b-form-spinbutton
                                                v-model="row.percentage"
                                                :min="1"
                                                :max="100"
                                                v-on:change="
                                                    setTerminData(
                                                        row.percentage,
                                                        'percen',
                                                        index
                                                    )
                                                "
                                            ></b-form-spinbutton>
                                        </td>
                                        <td>
                                            <date-picker
                                                v-model="row.due_date"
                                                format="YYYY-MM-DD"
                                                value-type="format"
                                                type="date"
                                                placeholder="Due Date"
                                                :disabled-date="
                                                    disabledBeforeToday
                                                "
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </b-card>
                    </div>
                </b-card>
            </div>
        </div>
        <div v-else-if="form.suspects[modelIndex].payment == 'Full'">
            <b-card class="col-md-12" bg-variant="light">
                <label>{{ $t("Due Date") }}</label>
                <date-picker
                    v-model="form.suspects[modelIndex].due_date"
                    format="YYYY-MM-DD"
                    value-type="format"
                    type="date"
                    placeholder="Due Date"
                    :disabled-date="disabledBeforeToday"
                />
            </b-card>
        </div>
        <div class="form-row">
            <b-card class="col-md-12" bg-variant="light">
                <label>{{ $t("Description") }}</label>
                <textarea
                    class="form-control"
                    placeholder="Description"
                    v-model="form.suspects[modelIndex].penaltyDescription"
                ></textarea>
            </b-card>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import Vue from "vue";
import selectEmployee from "../../../../components/elements/custom/select-employee.vue";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    props: ["modelIndex", "status"],
    data() {
        return {
            value: "",
            penalty: "",
            okStatus: false,
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
            paymentOptions: [
                { text: this.$tc("Full"), value: "Full" },
                { text: this.$tc("Installment"), value: "Installment" }
            ],
            periodTypeOptions: [
                { text: this.$tc("Week"), value: "Week" },
                { text: this.$tc("Month"), value: "Month" }
            ]
        };
    },
    created() {
        this.loadEmployees();
        this.loadmemorandumParameters();
        this.loaIncidentCategories();
        this.showPenalty({ id: this.modelIndex, status: this.status });
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("approveIntterogation", {
            form: state => state.form, //MENGAMBIL cSTATE DATA
            formPenalty: state => state.formPenalty
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
            showPenalty: "showPenalty"
        }),
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        },
        isNumber(e) {
            let char = String.fromCharCode(e.keyCode); // Get the character
            // console.log(value);
            console.log("event", e);
            if (/^([1-9]|1[0-9]|2[0-4])$/.test(char)) return true;
            // Match with regex
            else e.preventDefault(); // If not match, don't add to input text
        },
        onComplete(index, e) {
            const maskRef = e.detail;
            this.form.penalties[index].amount = maskRef.unmaskedValue;
        },
        onCompletePenalty(index, e) {
            const maskRef = e.detail;
            // this.penalty = maskRef.unmaskedValue;
            this.form.suspects[index].penalty = maskRef.unmaskedValue;
        },
        disabledBeforeToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date < today;
        },
        clearData() {
            this.form.suspects[this.modelIndex].penalties = [];
            this.okStatus = false;
        },
        setTerminData(data, calculate_by, index) {
            var i = 0;
            console.log(data);
            var total_termin = parseInt(
                this.form.suspects[this.modelIndex].installment_count
            );
            var total_penalty = this.form.suspects[this.modelIndex].penalty;
            var total_nominal = 0;
            var total_percen = 0;
            if (calculate_by == "percen") {
                data = data > 100 ? 100 : parseFloat(data);
            } else {
                data = parseFloat(data);
            }

            if (calculate_by == "nominal") {
                if (data > total_penalty || data === isNaN(data)) {
                    data = parseFloat(total_penalty);
                    this.form.suspects[this.modelIndex].penalties[
                        index
                    ].amount = data;
                    this.form.suspects[this.modelIndex].penalties[
                        index
                    ].percentage =
                        (parseFloat(data) /
                            this.form.suspects[this.modelIndex].penalty) *
                        100;
                } else {
                    data = parseFloat(data);
                    this.form.suspects[this.modelIndex].penalties[
                        index
                    ].amount = data;
                    this.form.suspects[this.modelIndex].penalties[
                        index
                    ].percentage =
                        (parseFloat(data) /
                            this.form.suspects[this.modelIndex].penalty) *
                        100;
                }
            } else {
                this.form.suspects[this.modelIndex].penalties[index].amount =
                    parseFloat(this.form.suspects[this.modelIndex].penalty) *
                    (data / 100);
                this.form.suspects[this.modelIndex].penalties[
                    index
                ].percentage = data;
            }

            for (i = 0; i <= index; i++) {
                total_nominal =
                    total_nominal +
                    parseFloat(
                        this.form.suspects[this.modelIndex].penalties[i].amount
                    );
                total_percen =
                    total_percen +
                    parseFloat(
                        this.form.suspects[this.modelIndex].penalties[i]
                            .percentage
                    );
            }

            for (i = index + 1; i < total_termin; i++) {
                this.form.suspects[this.modelIndex].penalties[i].percentage =
                    (100 - total_percen) / (total_termin - (index + 1));
                this.form.suspects[this.modelIndex].penalties[i].amount =
                    (this.form.suspects[this.modelIndex].penalty -
                        total_nominal) /
                    (total_termin - (index + 1));
            }
        },
        getDataPenalty() {
            this.okStatus = true;
            this.form.suspects[this.modelIndex].penalties = [];
            var i;
            var amount =
                this.form.suspects[this.modelIndex].penalty /
                this.form.suspects[this.modelIndex].installment_count;
            var percentage =
                (amount / this.form.suspects[this.modelIndex].penalty) * 100;

            for (
                i = 0;
                i < this.form.suspects[this.modelIndex].installment_count;
                i++
            ) {
                this.form.suspects[this.modelIndex].penalties.push({
                    amount: amount,
                    percentage: percentage,
                    due_date: null
                });
            }
        },
        getDataPenaltyWeek(date) {
            this.okStatus = true;
            this.form.suspects[this.modelIndex].penalties = [];
            // console.log("array", this.form.penalties);
            var i;
            var amount =
                this.form.suspects[this.modelIndex].penalty /
                this.form.suspects[this.modelIndex].installment_count;
            let currrentDate = new Date(date);
            var period = parseInt(this.form.suspects[this.modelIndex].period);
            let fixDate = new Date(
                currrentDate.setDate(currrentDate.getDate() - 7 * period)
            );
            var percentage =
                (amount / this.form.suspects[this.modelIndex].penalty) * 100;

            for (
                i = 0;
                i < this.form.suspects[this.modelIndex].installment_count;
                i++
            ) {
                let dueDate = new Date(
                    fixDate.setDate(fixDate.getDate() + 7 * period)
                );
                console.log("last", dueDate);
                var due_date =
                    dueDate.getFullYear() +
                    "-" +
                    (dueDate.getMonth() + 1) +
                    "-" +
                    dueDate.getDate();
                this.form.suspects[this.modelIndex].penalties.push({
                    amount: amount,
                    percentage: percentage,
                    due_date: due_date
                });
            }
            // console.log("data", this.form.penalties);
        },
        getDataPenaltyMonth(date) {
            this.okStatus = true;
            this.form.suspects[this.modelIndex].penalties = [];
            // console.log("array", this.form.penalties);
            var i;
            var amount =
                this.form.suspects[this.modelIndex].penalty /
                this.form.suspects[this.modelIndex].installment_count;
            let currrentDate = new Date(date);
            var period = parseInt(this.form.suspects[this.modelIndex].period);
            let fixDate = new Date(
                currrentDate.setMonth(currrentDate.getMonth() - period)
            );
            var percentage =
                (amount / this.form.suspects[this.modelIndex].penalty) * 100;

            for (
                i = 0;
                i < this.form.suspects[this.modelIndex].installment_count;
                i++
            ) {
                let dueDate = new Date(
                    fixDate.setMonth(fixDate.getMonth() + period)
                );
                console.log("last", dueDate);
                var due_date =
                    dueDate.getFullYear() +
                    "-" +
                    (dueDate.getMonth() + 1) +
                    "-" +
                    dueDate.getDate();
                this.form.suspects[this.modelIndex].penalties.push({
                    amount: amount,
                    percentage: percentage,
                    due_date: due_date
                });
            }
            // console.log("data", this.form.penalties);
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
