<template>
    <div class="card">
        <div class="card-body">
            <!-- Card Header -->
            <div class="row mb-2">
                <div class="col-sm-4">
                    <div class="search-box mr-2 mb-2 d-inline-block">
                        <div class="position-relative"></div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="float-right"></div>
                </div>
            </div>
            <!-- End Card Header -->
            <b-tabs content-class="mt-3">
                <b-tab active>
                    <template v-slot:title>
                        <span class="mdi mdi-clipboard-alert"></span
                        >&nbsp;Interrogation Report
                    </template>
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
                        <div class="col-md-3">
                            <label>{{ $tc("Filter Data") }}</label>
                            <v-select
                                v-model="interrogationStatus"
                                :options="interrogationOptions"
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
                        <template v-slot:head()="data">{{
                            $t(data.label)
                        }}</template>
                        <template v-slot:cell(index)="row">{{
                            tableParams.page !== 1
                                ? row.index +
                                  1 +
                                  tableParams.per_page * (tableParams.page - 1)
                                : row.index + 1
                        }}</template>
                        <template v-slot:cell(code)="row">
                            <!-- `data.value` is the value after formatted by the Formatter -->
                            <a
                                href="javascript:void(0)"
                                @click="viewInterrogation(row.item.id)"
                                v-b-tooltip.hover
                                :title="$tc('detail')"
                                >{{ row.item.code }}</a
                            >
                        </template>
                        <template v-slot:cell(lost_cost)="row">
                            Rp. {{ numberWithCommas(row.item.lost_cost) }}
                        </template>
                        <template v-slot:cell(is_active)="row">
                            <span
                                class="badge badge-pill badge-soft-success font-size-12"
                                v-if="row.item.is_active == 0"
                                >{{ $t("approved") }}</span
                            >
                            <span
                                class="badge badge-pill badge-soft-primary font-size-12"
                                v-if="row.item.is_active == 1"
                                >{{ $t("waiting approval") }}</span
                            >
                            <span
                                class="badge badge-pill badge-soft-danger font-size-12"
                                v-else-if="row.item.is_active == 2"
                                >{{ $t("rejected") }}</span
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
                                v-if="
                                    collection.data &&
                                        collection.data.length > 0
                                "
                            ></b-pagination>
                        </div>
                    </div>
                </b-tab>
                <b-tab>
                    <template v-slot:title>
                        <span class="mdi mdi-coin"></span>&nbsp;Incident
                        Penalties
                    </template>
                    <incidentPenalty></incidentPenalty>
                </b-tab>
                <b-tab>
                    <template v-slot:title>
                        <span class="mdi mdi-clipboard-text"></span
                        >&nbsp;Memorandum
                    </template>
                    <memorandum></memorandum>
                </b-tab>
            </b-tabs>
        </div>
        <b-modal
            id="form-interrogation"
            size="lg"
            :title="$t('History')"
            ref="modal"
        >
            <form>
                <formInterrogation :modelId="modelId"></formInterrogation>
            </form>
        </b-modal>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
import formInterrogation from "./detail.vue";
import memorandum from "../memorandum/memorandum.vue";
import incidentPenalty from "../incident-penalty/incident-penalty.vue";
import vSelect from "vue-select";
export default {
    created() {
        this.load(this.tableParams);
    },
    data() {
        return {
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
            fields: [
                {
                    key: "index",
                    label: "#",
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "code",
                    label: "Code",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "incident_category.name",
                    label: "incident category",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "date_of_incident",
                    label: "date of incident",
                    sortable: true,
                    thStyle: "text-align: center;",
                    tdClass: "text-center"
                },
                {
                    key: "lost_cost",
                    label: "lost cost",
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
            interrogationOptions: [
                { text: this.$tc("Approved"), value: 0 },
                { text: this.$tc("Waiting Approval"), value: 1 },
                { text: this.$tc("Rejected"), value: 2 }
            ],
            interrogationStatus: ""
        };
    },
    components: {
        formInterrogation,
        memorandum,
        incidentPenalty,
        "v-select": vSelect
    },
    computed: {
        ...mapState("reportInterrogation", {
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
                    .reportInterrogation.tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/reportInterrogation/SET_TABLE_PARAMS",
                    params
                );
            }
        },
        ...mapState("reportInterrogation", {
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
        interrogationStatus() {
            let requestParams = {
                interrogationStatus: this.interrogationStatus,
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
        ...mapActions("reportInterrogation", ["load", "showInterrogation"]),
        viewInterrogation(id) {
            this.showInterrogation(id);
            this.modelId = id;
            this.$bvModal.show("form-interrogation");
        },
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        }
    }
};
</script>
