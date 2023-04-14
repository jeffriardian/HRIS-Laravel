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
                        <!-- <b-dropdown>
                            <template v-slot:button-content>
                                {{ $t("export") }}
                                <i class="mdi mdi-chevron-down"></i>
                            </template>
                            <b-dropdown-item @click="exportFile('excel')">
                                <i class="mdi mdi-file-excel"></i> Excel
                            </b-dropdown-item>
                            <b-dropdown-item @click="exportFile('pdf')">
                                <i class="mdi mdi-file-pdf"></i> PDF
                            </b-dropdown-item>
                        </b-dropdown> -->
                        <b-button
                            v-b-modal="'form-modal'"
                            class="btn btn-success ml-2"
                        >
                            <i class="mdi mdi-plus mr-1"></i>
                            {{ $tc("create") }}
                        </b-button>
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
                <template v-slot:head()="data">{{ $t(data.label) }}</template>
                <template v-slot:cell(index)="row">{{
                    tableParams.page !== 1
                        ? row.index +
                          1 +
                          tableParams.per_page * (tableParams.page - 1)
                        : row.index + 1
                }}</template>
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
                <template v-slot:cell(lost_cost)="row">
                    Rp. {{ numberWithCommas(row.item.lost_cost) }}
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- <router-link
                        :to="{
                            name: 'interrogationReports.detail',
                            params: { id: row.item.id }
                        }"
                        class="text-info"
                        v-b-tooltip.hover
                        title="Detail"
                    >
                        <i class="mdi mdi-eye font-size-16"></i>
                    </router-link> -->
                    <a
                        href="javascript:void(0)"
                        @click="view(row.item.id)"
                        class="text-primary"
                        v-b-tooltip.hover
                        :title="$tc('update')"
                    >
                        <i class="mdi mdi-pencil-box-outline font-size-16"></i>
                    </a>
                    <a
                        href="javascript:void(0)"
                        @click="remove(row.item)"
                        class="text-danger"
                        v-b-tooltip.hover
                        :title="$tc('delete')"
                    >
                        <i class="mdi mdi-trash-can-outline font-size-16"></i>
                    </a>
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
            id="form-modal"
            size="lg"
            :title="$t('form module', { module: $tc('interrogation report') })"
            ref="modal"
            @hide="clearModelId()"
        >
            <form v-on:submit.prevent="submit">
                <formComponent :modelId="modelId"></formComponent>
            </form>
            <template v-slot:modal-footer>
                <b-button class="btn btn-secondary ml-2" @click="hideModal()">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}
                </b-button>
                <button
                    :disabled="loadingProcess"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-else class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
            </template>
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
                },
                {
                    key: "actions",
                    label: "action",
                    thStyle: "text-align: center;",
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
        ...mapState("interrogationReport", {
            collection: state => state.collection
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        ...mapState(["errors"]),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.interrogationReport
                    .tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/interrogationReport/SET_TABLE_PARAMS",
                    params
                );
            }
        },
        ...mapState("interrogationReport", {
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
        }
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions("interrogationReport", [
            "load",
            "show",
            "store",
            "update",
            "destroy"
        ]),
        clearModelId() {
            this.modelId = null;
        },
        numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".");
        },
        hideModal() {
            this.loadingProcess = false;
            this.load(this.tableParams);
            this.$bvModal.hide("form-modal");
        },
        view(id) {
            this.show({ id: id, action: "edit" }).then(() => {
                this.$bvModal.show("form-modal");
            });
            this.modelId = id;
        },
        submit() {
            this.loadingProcess = true;
            let form = new FormData();
            for (const property in this.form) {
                if (property == "images") {
                    this.form.images.forEach((element, index) => {
                        console.log(element);
                        if (element.id) {
                            form.append(`images[${index}][id]`, element.id);
                            form.append(
                                `images[${index}][image]`,
                                element.caption
                            );
                            form.append(`images[${index}][note]`, element.note);
                        } else {
                            form.append(
                                `images[${index}][image]`,
                                element.caption
                            );
                            form.append(`images[${index}][note]`, element.note);
                        }
                    });
                    if (this.form.actor_witness != null) {
                        this.form.actor_witness.forEach((element, index) => {
                            if (element.id) {
                                form.append(
                                    `witness_actor[${index}][id]`,
                                    element.id
                                );
                            }
                            form.append(
                                `witness_actor[${index}][employeeStatus]`,
                                element.employeeStatus
                            );
                            form.append(
                                `witness_actor[${index}][witnesses]`,
                                element.witnesses
                            );
                            form.append(
                                `witness_actor[${index}][external]`,
                                element.external
                            );
                            form.append(
                                `witness_actor[${index}][description]`,
                                element.description
                            );
                        });
                    }
                    if (this.form.actor_suspects != null) {
                        this.form.actor_suspects.forEach((element, index) => {
                            if (element.id) {
                                form.append(
                                    `suspect_actor[${index}][id]`,
                                    element.id
                                );
                            }
                            form.append(
                                `suspect_actor[${index}][employeeStatus]`,
                                element.employeeStatus
                            );
                            form.append(
                                `suspect_actor[${index}][suspects]`,
                                element.suspects
                            );
                            form.append(
                                `suspect_actor[${index}][external]`,
                                element.external
                            );
                            form.append(
                                `suspect_actor[${index}][description]`,
                                element.description
                            );
                        });
                    }
                } else {
                    form.append(`${property}`, this.form[property]);
                }
            }
            if (this.modelId) {
                form.append("_method", "patch");
            }
            if (!this.modelId) {
                this.store(form).then(() => {
                    this.hideModal();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("interrogation report"),
                            message: this.$tc("add", 2)
                        })
                    });
                });
            } else {
                this.update({ id: this.modelId, form: form }).then(() => {
                    this.hideModal();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("interrogation report"),
                            message: this.$tc("update", 2)
                        })
                    });
                });
            }
        },
        remove(item) {
            this.$confirm(
                this.$t("delete confirmation", {
                    item: item.name,
                    module: this.$tc("interrogation report")
                }),
                this.$t("warning"),
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
                        message: this.$t("delete successfully")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("delete canceled")
                    });
                });
        }
    }
};
</script>
