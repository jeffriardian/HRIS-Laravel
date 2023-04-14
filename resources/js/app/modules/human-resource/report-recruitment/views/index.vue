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
                <div class="col-sm-8">
                    <div class="float-right">
                        <b-dropdown>
                            <template v-slot:button-content>
                                {{ $t("export") }}
                                <i class="mdi mdi-chevron-down"></i>
                            </template>
                            <b-dropdown-item @click="exportFile('excel')"
                                ><i class="mdi mdi-file-excel"></i>
                                Excel</b-dropdown-item
                            >
                            <b-dropdown-item @click="exportFile('pdf')"
                                ><i class="mdi mdi-file-pdf"></i>
                                PDF</b-dropdown-item
                            >
                        </b-dropdown>
                    </div>
                </div>
            </div>
            <!-- End Card Header -->
            <div>
                <b-tabs content-class="mt-3">
                    <b-tab title="Recruitment" active>
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
                                          tableParams.per_page *
                                              (tableParams.page - 1)
                                        : row.index + 1
                                }}
                            </template>
                            <template v-slot:cell(is_active)="row">
                                <span
                                    class="badge badge-pill badge-soft-success font-size-12"
                                    v-if="row.item.is_active == 1"
                                    >{{ $t("active") }}</span
                                >
                                <span
                                    class="badge badge-pill badge-soft-danger font-size-12"
                                    v-else
                                    >{{ $t("inactive") }}</span
                                >
                            </template>
                        </b-table>
                    </b-tab>
                    <b-tab title="Not Attend">
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
                                          tableParams.per_page *
                                              (tableParams.page - 1)
                                        : row.index + 1
                                }}
                            </template>
                            <template v-slot:cell(is_active)="row">
                                <span
                                    class="badge badge-pill badge-soft-success font-size-12"
                                    v-if="row.item.is_active == 1"
                                    >{{ $t("active") }}</span
                                >
                                <span
                                    class="badge badge-pill badge-soft-danger font-size-12"
                                    v-else
                                    >{{ $t("inactive") }}</span
                                >
                            </template>
                        </b-table>
                    </b-tab>
                    <b-tab title="Candidate">
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
                                          tableParams.per_page *
                                              (tableParams.page - 1)
                                        : row.index + 1
                                }}
                            </template>
                            <template v-slot:cell(is_active)="row">
                                <span
                                    class="badge badge-pill badge-soft-success font-size-12"
                                    v-if="row.item.is_active == 1"
                                    >{{ $t("active") }}</span
                                >
                                <span
                                    class="badge badge-pill badge-soft-danger font-size-12"
                                    v-else
                                    >{{ $t("inactive") }}</span
                                >
                            </template>
                        </b-table>
                    </b-tab>
                </b-tabs>
            </div>
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
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
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
                { key: "name", label: "Candidate Name", sortable: true },
                {
                    key: "recruitment_step",
                    label: "Recruitment Step",
                    sortable: true
                },
                {
                    key: "status_recruitment",
                    label: "Status Recruitment",
                    sortable: true
                },
                {
                    key: "is_active",
                    label: "status",
                    sortable: true,
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
            Attendfields: [
                {
                    key: "index",
                    label: "#",
                    thStyle: "text-align: center; width: 35px;",
                    tdClass: "text-center"
                },
                { key: "name", label: "Candidate Name", sortable: true },
                {
                    key: "recruitment_step",
                    label: "Recruitment Step",
                    sortable: true
                },
                {
                    key: "status_recruitment",
                    label: "Status Recruitment",
                    sortable: true
                },
                {
                    key: "is_active",
                    label: "status",
                    sortable: true,
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
            Candidatefields: [
                {
                    key: "index",
                    label: "#",
                    thStyle: "text-align: center; width: 35px;",
                    tdClass: "text-center"
                },
                { key: "name", label: "Candidate Name", sortable: true },
                {
                    key: "status_recruitment",
                    label: "Status Recruitment",
                    sortable: true
                }
            ],
            keyword: "",
            modelId: null,
            loadingProcess: false
        };
    },
    components: {},
    computed: {
        ...mapState("reportRecruitment", {
            collection: state => state.collection
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.recruitmentStepParameter
                    .tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/reportRecruitment/SET_TABLE_PARAMS",
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
        ...mapActions("reportRecruitment", [
            "load",
            "show",
            "store",
            "update",
            "destroy"
        ]),
        exportFile(type) {
            window.open(
                `/api/human-resource/report-recruitment-export-${type}?api_token=${this.$store.state.token}`
            );
        }
    }
};
</script>
