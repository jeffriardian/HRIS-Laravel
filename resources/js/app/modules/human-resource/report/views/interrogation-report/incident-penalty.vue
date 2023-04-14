<template>
    <div>
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
                                            {{ $t("Penalty") }}
                                        </th>
                                        <th>
                                            {{ $t("Installment Count") }}
                                        </th>
                                        <th>
                                            {{ $t("Action") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="formPenalty.length == 0">
                                        <td colspan="3" class="text-center">
                                            <h5>
                                                {{ $t("No History Recorded") }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr
                                        v-else
                                        v-for="(row, index) in formPenalty"
                                        :key="index"
                                        class="justify-content-between align-items-center"
                                    >
                                        <td>
                                            <b-input-group
                                                prepend="Rp."
                                                class="mb-2 mr-sm-2 mb-sm-0"
                                            >
                                                <b-input
                                                    class="form-control"
                                                    :value="
                                                        numberWithCommas(
                                                            row.penalty
                                                        )
                                                    "
                                                    disabled
                                                ></b-input>
                                            </b-input-group>
                                            <b-collapse
                                                :id="`detail-data-penalty`"
                                                class="mt-2 col-md-12"
                                            >
                                                <b-card>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div
                                                                class="form-row"
                                                            >
                                                                <table
                                                                    class="table table-bordered table-sm table-hover"
                                                                >
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                &nbsp;{{
                                                                                    $t(
                                                                                        "Amount"
                                                                                    )
                                                                                }}
                                                                            </th>
                                                                            <th>
                                                                                {{
                                                                                    $t(
                                                                                        "Due Date"
                                                                                    )
                                                                                }}
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr
                                                                            class="justify-content-between align-items-center"
                                                                            v-for="(row2,
                                                                            index) in row.incident_penalty_details"
                                                                            :key="
                                                                                index
                                                                            "
                                                                        >
                                                                            <td>
                                                                                <b-input-group
                                                                                    prepend="Rp."
                                                                                    class="mb-2 mr-sm-2 mb-sm-0"
                                                                                >
                                                                                    <b-input
                                                                                        class="form-control"
                                                                                        :value="
                                                                                            numberWithCommas(
                                                                                                row2.amount
                                                                                            )
                                                                                        "
                                                                                        disabled
                                                                                    ></b-input>
                                                                                </b-input-group>
                                                                            </td>
                                                                            <td>
                                                                                <date-picker
                                                                                    v-model="
                                                                                        row2.due_date
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
                                                </b-card>
                                            </b-collapse>
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                class="form-control"
                                                disabled
                                                v-model="row.installment_count"
                                            />
                                        </td>
                                        <td>
                                            <a
                                                href="javascript:void(0)"
                                                v-b-toggle="
                                                    `detail-data-penalty`
                                                "
                                                class="text-primary"
                                                v-b-tooltip.hover
                                                :title="$tc('show detail')"
                                            >
                                                <i
                                                    class="mdi mdi-eye font-size-16"
                                                ></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import vSelect from "vue-select";
import { mapState, mapMutations, mapActions } from "vuex";
import { IMaskDirective } from "vue-imask";

export default {
    data() {
        return {
            value: "",
            detailStatus: false,
            penalty_id: "",
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
            }
        };
    },
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("reportInterrogation", {
            formPenalty: state => state.formPenalty
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("reportInterrogation", ["CLEAR_FORM"]),
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
    },
    components: {
        "v-select": vSelect
    },
    directives: {
        imask: IMaskDirective
    }
};
</script>
