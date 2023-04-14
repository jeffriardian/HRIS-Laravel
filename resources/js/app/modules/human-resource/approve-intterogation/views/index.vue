<template>
    <div class="card">
        <div class="card-body">
            <!-- Card Header -->
            <div class="row mb-2">
                <div class="col-sm-4">
                    <div class="search-box mr-2 mb-2 d-inline-block">
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
                </div>
            </div>
            <!-- End Card Header -->
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
                <template v-slot:head()="data">
                    {{ $t(data.label) }}
                </template>
                <template v-slot:cell(index)="row">
                    {{
                        tableParams.page !== 1
                            ? row.index +
                              1 +
                              tableParams.per_page * (tableParams.page - 1)
                            : row.index + 1
                    }}
                </template>
                <template v-slot:cell(lost_cost)="row">
                    Rp. {{ numberWithCommas(row.item.lost_cost) }}
                </template>
                <template v-slot:cell(is_active)="row">
                    <span
                        class="badge badge-pill badge-soft-info font-size-12"
                        v-if="row.item.is_active == 1"
                        >{{ $t("waiting approval") }}</span
                    >
                    <span
                        class="badge badge-pill badge-soft-danger font-size-12"
                        v-else
                        >{{ $t("inactive") }}</span
                    >
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- <a href='javascript:void(0)' @click="view(row.item.id)" class="text-primary" v-b-tooltip.hover :title="$tc('update')"><i class="mdi mdi-pencil-box-outline font-size-16"></i></a> -->
                    <a
                        href="javascript:void(0)"
                        @click="view(row.item.id)"
                        class="text-primary"
                        :title="$tc('Approve')"
                        v-b-tooltip.hover
                        ><i
                            class="mdi mdi-checkbox-marked-circle font-size-16"
                        ></i
                    ></a>
                </template>
            </b-table>
        </div>
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
        <b-modal
            size="lg"
            id="form-modal"
            hide-footer
            :title="$t('form module', { module: $tc('Approve Intterogation') })"
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formComponent
                    :modelId="modelId"
                    @onAccepted="changeAccepted"
                ></formComponent>
            </form>
        </b-modal>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
import formComponent from "./form.vue";
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
                    thStyle: "text-align: center; width: 35px;",
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
                    thStyle: "text-align: center; width: 120px;",
                    tdClass: "text-center"
                },
                {
                    key: "actions",
                    label: "action",
                    thStyle: "text-align: center; width: 80px;",
                    tdClass: "text-center"
                }
            ],
            keyword: "",
            modelId: null,
            loadingProcess: false
        };
    },
    components: {
        formComponent
    },
    computed: {
        ...mapState("approveIntterogation", {
            collection: state => state.collection
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.approveIntterogation
                    .tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/approveIntterogation/SET_TABLE_PARAMS",
                    params
                );
            }
        }
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
        }
    },
    mounted() {
        this.$root.$on("bv::modal::hidden", (bvEvent, modalId) => {
            this.modelId = null;
        });
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions("approveIntterogation", [
            "load",
            "show",
            "store",
            "update",
            "destroy",
            "approve"
        ]),
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        },
        changeAccepted(value) {
            this.load(this.tableParams);
            this.$bvModal.hide("form-modal");
        },
        view(id) {
            this.show(id).then(() => {
                this.$bvModal.show("form-modal");
            });
            this.modelId = id;
        },
        remove(item) {
            this.$confirm(
                this.$t("Approve Confirmation?", {
                    item: item.employee,
                    module: this.$tc("Approve Reimburse")
                }),
                this.$t("Confirmation"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    this.destroy(item.id);
                    this.$message({
                        type: "success",
                        message: this.$t("approve successfully")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("approve canceled")
                    });
                });
        },
        appReimburse(item) {
            this.$confirm(
                this.$t("Approve Confirmation?", {
                    item: item.employee,
                    module: this.$tc("Approve Reimburse")
                }),
                this.$t("Confirmation"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    this.approve(item.id);
                    this.$message({
                        type: "success",
                        message: this.$t("approve successfully")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("approve canceled")
                    });
                });
        }
    }
};
</script>
