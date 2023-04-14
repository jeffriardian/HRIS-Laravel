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
                    v-model="memorandumStatus"
                    :options="memorandumOptions"
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
            <template v-slot:cell(is_active)="row">
                <span
                    class="badge badge-pill badge-soft-success font-size-12"
                    v-if="row.item.is_active == 1"
                    >{{ $t("Valid") }}</span
                >
                <span
                    class="badge badge-pill badge-soft-danger font-size-12"
                    v-else-if="row.item.is_active == 0"
                    >{{ $t("Not Valid") }}</span
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
                    label: "name",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "memorandum_parameter.name",
                    label: "memorandum parameter",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "start_date",
                    label: "Start Date",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "end_date",
                    label: "End Date",
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
            nowDate: null,
            memorandumStatus: null,
            finishDate: null,
            loadingProcess: false,
            memorandumOptions: [
                { text: this.$tc("Valid"), value: 1 },
                { text: this.$tc("Not Valid"), value: 0 }
            ]
        };
    },
    components: {
        "v-select": vSelect
    },
    computed: {
        ...mapState("reportMemorandum", {
            collection: state => state.collection
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        ...mapState(["errors"]),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.report.reportMemorandum
                    .tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/reportMemorandum/SET_TABLE_PARAMS",
                    params
                );
            }
        },
        ...mapState("reportMemorandum", {
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
        memorandumStatus() {
            let requestParams = {
                memorandumStatus: this.memorandumStatus,
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
        }
    },
    mounted() {
        this.$root.$on("bv::modal::hidden", (bvEvent, modalId) => {
            this.modelId = null;
        });
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions("reportMemorandum", ["load"]),
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        },
        qalqulateDate(end_date) {
            var oneDay = 24 * 60 * 60 * 1000;
            this.nowDate = new Date();
            this.finishDate = new Date(end_date);
            this.diffDays = Math.round(
                Math.round(
                    (this.finishDate.getTime() - this.nowDate.getTime()) /
                        oneDay
                )
            );

            if (this.diffDays > 0) {
                this.memorandumStatus = 1;
            } else {
                this.memorandumStatus = 0;
            }
            // return this.memorandumStatus;
        }
    }
};
</script>
