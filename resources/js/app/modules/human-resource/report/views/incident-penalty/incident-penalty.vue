<template>
    <div>
        <div class="form-row">
            <div
                class="form-group col-md-3 search-box mr-2 mb-2 d-inline-block"
            >
                <br />
                <div class="position-relative">
                    <input
                        type="text"
                        class="form-control"
                        :placeholder="$t('search')"
                        v-model="keyword"
                    />
                    <i class="bx bx-search-alt search-icon"></i>
                </div>
            </div>
            <div class="col-md-5"></div>
            <div class="form-group col-md-3">
                <label>{{ $tc("Filter Data") }}</label>
                <v-select
                    v-model="penaltyStatus"
                    :options="penaltyOptions"
                    label="text"
                    :reduce="text => text.value"
                ></v-select>
            </div>
        </div>
        <b-table
            :sort-by.sync="tableParams.order_column"
            :sort-desc.sync="tableParams.order_direction"
            responsive="sm"
            thead-class="thead-light"
            small
            hover
            bordered
            :items="collection.data"
            :fields="fields"
            show-empty
        >
            <template v-slot:head()="data">{{ $t(data.label) }}</template>
            <template v-slot:cell(index)="row">{{
                tableParams.page !== 1
                    ? row.index +
                      1 +
                      tableParams.per_page * (tableParams.page - 1)
                    : row.index + 1
            }}</template>
            <template v-slot:cell(interrogation_report_actor_id)="row">
                <div
                    v-if="row.item.interrogation_report_actor.employee_id != 0"
                >
                    {{ row.item.interrogation_report_actor.employee.name }}
                </div>
                <div v-else>
                    {{
                        row.item.interrogation_report_actor.external_employee
                            .name
                    }}
                </div>
            </template>
            <template v-slot:cell(penalty)="row">
                Rp.{{ numberWithCommas(row.item.penalty) }}
            </template>
            <template v-slot:cell(type_of_installment)="row">
                <span
                    class="badge badge-pill badge-soft-info font-size-12"
                    v-if="row.item.type_of_installment == 0"
                    >{{ $t("Installment") }}</span
                >
            </template>
            <template v-slot:cell(is_active)="row">
                <span
                    class="badge badge-pill badge-soft-success font-size-12"
                    v-if="row.item.is_active == 0"
                    >{{ $t("Done") }}</span
                >
                <span
                    class="badge badge-pill badge-soft-danger font-size-12"
                    v-else-if="row.item.is_active == 1"
                    >{{ $t("Not Yet") }}</span
                >
            </template>
        </b-table>
        <div
            class="card-footer bg-white d-flex justify-content-between align-items-center"
        >
            <div>
                <b-form-select
                    v-model="tableParams.per_page"
                    :options="tableParams.pageOptions"
                ></b-form-select>
            </div>
            <div>
                <p v-if="collection.data">
                    {{
                        $t("table summary", {
                            from: collection.meta.from,
                            to: collection.meta.to,
                            total: collection.meta.total
                        })
                    }}
                </p>
            </div>
            <div>
                <b-pagination
                    v-model="tableParams.page"
                    pills
                    :total-rows="collection.meta.total"
                    :per-page="collection.meta.per_page"
                    v-if="collection.data && collection.data.length > 0"
                ></b-pagination>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
import vSelect from "vue-select";
export default {
    created() {
        this.load(this.tableParams);
    },
    data() {
        return {
            fields: [
                {
                    key: "index",
                    label: "#",
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "interrogation_report_actor_id",
                    label: "Name",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "penalty",
                    label: "Penalty",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "installment_count",
                    label: "installment count",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "type_of_installment",
                    label: "type of installment",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "is_active",
                    label: "status",
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                }
            ],
            keyword: "",
            modelId: null,
            loadingProcess: false,
            penaltyOptions: [
                { text: this.$tc("Done"), value: 0 },
                { text: this.$tc("Not Yet"), value: 1 }
            ],
            penaltyStatus: ""
        };
    },
    components: {
        "v-select": vSelect
    },
    computed: {
        ...mapState("reportIncidentPenalty", {
            collection: state => state.collection
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        ...mapState(["errors"]),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.report
                    .reportIncidentPenalty.tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/reportIncidentPenalty/SET_TABLE_PARAMS",
                    params
                );
            }
        },
        ...mapState("reportIncidentPenalty", {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    watch: {
        tableParams: {
            handler: function(params) {
                let requestParams = {
                    keyword: this.keyword,
                    page: params.page,
                    per_page: params.per_page,
                    order_column: params.order_column,
                    order_direction: params.order_direction
                };
                this.load(requestParams);
            },
            deep: true
        },
        keyword() {
            let requestParams = {
                keyword: this.keyword,
                page: 1,
                per_page: this.tableParams.per_page,
                order_column: this.tableParams.order_column,
                order_direction: this.tableParams.order_direction
            };
            this.load(requestParams);
        },
        notify() {
            this.load(this.tableParams);
        },
        errors() {
            if (typeof this.errors === "object") {
                if (Object.keys(this.errors).length > 0) {
                    this.loadingProcess = false;
                }
            }
        },
        penaltyStatus() {
            let requestParams = {
                penaltyStatus: this.penaltyStatus,
                page: 1,
                per_page: this.tableParams.per_page,
                order_column: this.tableParams.order_column,
                order_direction: this.tableParams.order_direction
            };
            this.load(requestParams);
        }
    },
    mounted() {
        this.$root.$on("bv::modal::hidden", (bvEvent, modalId) => {
            this.modelId = null;
        });
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions("reportIncidentPenalty", ["load"]),
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        }
    }
};
</script>
