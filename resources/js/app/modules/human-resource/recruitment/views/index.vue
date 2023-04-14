<template>
    <div class="card">
        <div class="card-body">
            <!-- Card Header -->
            <div class="row mb-2">
                <div class="col-sm-8 d-flex pt-1">
                    <div class="col-sm-6 d-inline-block">
                        <div class="search-box mr-2 mb-2">
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
                    <div class="col-sm-6">
                        <div class="search-box mr-2">
                            <v-select
                                size="lg"
                                v-model="recruitmentStepId"
                                :options="recruitmentSteps.data"
                                label="name"
                                :reduce="name => name.id"
                            ></v-select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="float-right">
                        <!--<b-dropdown>
                            <template v-slot:button-content>
                                {{ $t('export') }}
                                <i class="mdi mdi-chevron-down"></i>
                            </template>
                            <b-dropdown-item @click="exportFile('excel')"><i class="mdi mdi-file-excel"></i> Excel</b-dropdown-item>
                            <b-dropdown-item @click="exportFile('pdf')"><i class="mdi mdi-file-pdf"></i> PDF</b-dropdown-item>
                        </b-dropdown> -->
                        <b-button
                            v-b-modal="'form-create'"
                            class="btn btn-success ml-2"
                            ><i class="mdi mdi-plus mr-1"></i>
                            {{ $tc("create") }}</b-button
                        >
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
                <template v-slot:cell(identity_card_number)="row">
                    {{ row.item.candidate.ktp }}
                </template>
                <template v-slot:cell(name)="row">
                    {{ row.item.candidate.name }}
                </template>
                <template v-slot:cell(email)="row">
                    {{ row.item.candidate.email }}
                </template>
                <template v-slot:cell(phone_number)="row">
                    {{ row.item.candidate.phone_number }}
                </template>
                <template v-slot:cell(position)="row">
                    {{ row.item.candidate.position.name }}
                </template>
                <template v-slot:cell(company)="row">
                    {{ row.item.candidate.company.name }}
                </template>
                <template v-slot:cell(recruitment_step)="row">
                    {{ row.item.recruitment_step.name }}
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
                <template v-slot:cell(actions)="row">
                    <a
                        href="javascript:void(0)"
                        @click="viewUpdate(row.item.id)"
                        class="text-primary"
                        v-b-tooltip.hover
                        :title="$tc('update')"
                        ><i class="mdi mdi-pencil-box-outline font-size-16"></i
                    ></a>
                    <a
                        href="javascript:void(0)"
                        @click="view(row.item.id)"
                        class="text-primary"
                        v-if="
                            row.item.recruitment_step.name !=
                                'Interview Direksi'
                        "
                        v-b-tooltip.hover
                        :title="$tc('assessment')"
                        ><i class="mdi mdi-clipboard-text font-size-16"></i
                    ></a>
                    <a
                        href="javascript:void(0)"
                        @click="viewFinal(row.item.candidate_id)"
                        class="text-primary"
                        v-if="row.item.recruitment_step.id == 4"
                        v-b-tooltip.hover
                        :title="$tc('final assessment')"
                        ><i class="mdi mdi-clipboard-text font-size-16"></i
                    ></a>
                    <a
                        href="javascript:void(0)"
                        @click="remove(row.item)"
                        class="text-danger"
                        v-b-tooltip.hover
                        :title="$tc('delete')"
                        ><i class="mdi mdi-trash-can-outline font-size-16"></i
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
            id="form-modal"
            hide-header-close
            no-close-on-esc
            no-close-on-backdrop
            size="xl"
            :title="$t('form module', { module: $tc('Recruitment') })"
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formComponent
                    :modelId="modelId"
                    @onAccepted="changeAccepted"
                    @onDisabledAccepted="changeDisabledAccepted"
                ></formComponent>
            </form>
            <template v-slot:modal-footer>
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="cancelAsessment()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    v-if="!acceptedStatus"
                    class="btn btn-primary"
                    @click.prevent="submitAsessment"
                    :disabled="loadingProcess"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-if="!loadingProcess" class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
                <button
                    v-if="acceptedStatus"
                    :disabled="!acceptedDisabledStatus"
                    href="javascript:void(0)"
                    @click="viewPersonal(modelId)"
                    class="btn btn-success ml-2"
                >
                    {{ $t("Next") }}<i class="fas fa-arrow-right ml-2"></i>
                </button>
            </template>
        </b-modal>

        <b-modal
            id="form-personal"
            hide-header-close
            no-close-on-esc
            no-close-on-backdrop
            size="xl"
            :title="
                $t('form module', { module: $tc('Personal Data Candidate') })
            "
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formPersonal :modelId="modelId"></formPersonal>
            </form>
            <template v-slot:modal-footer>
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="cancelAsessment()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    class="btn btn-primary"
                    @click.prevent="submitAsessment"
                    :disabled="loadingProcess"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-if="!loadingProcess" class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
            </template>
        </b-modal>

        <b-modal
            id="form-create"
            no-close-on-backdrop
            size="lg"
            :title="$t('form module', { module: $tc('Candidate') })"
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formCreate :modelId="modelId"></formCreate>
            </form>
            <template v-slot:modal-footer>
                <b-button class="btn btn-secondary ml-2" @click="cancelCreate()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    class="btn btn-primary"
                    @click.prevent="submit"
                    :disabled="loadingProcess"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-if="!loadingProcess" class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
            </template>
        </b-modal>

        <b-modal
            id="form-update"
            no-close-on-backdrop
            size="xl"
            :title="$t('form module', { module: $tc('Candidate') })"
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formUpdate :modelId="modelId"></formUpdate>
            </form>
            <template v-slot:modal-footer>
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="hideModalUpdate()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    class="btn btn-primary"
                    @click.prevent="submit"
                    :disabled="loadingProcess"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-if="!loadingProcess" class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
            </template>
        </b-modal>

        <b-modal
            id="form-final"
            no-close-on-backdrop
            size="xl"
            :title="$t('form module', { module: $tc('Final Test') })"
            ref="modal"
        >
            <form v-on:submit.prevent="submit">
                <formFinal
                    :modelId="modelId"
                    v-on:childToParent="onChildClick"
                ></formFinal>
            </form>
            <template v-slot:modal-footer>
                <b-button
                    class="btn btn-secondary ml-2"
                    @click="hideModalFinal()"
                    ><i class="fas fa-arrow-left mr-2"></i>
                    {{ $t("cancel") }}</b-button
                >
                <button
                    class="btn btn-danger"
                    @click.prevent="submitReject"
                    :disabled="loadingProcess"
                    v-if="!reschedule"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i
                        v-if="!loadingProcess"
                        class="fa fa-window-close mr-2"
                    ></i>
                    {{ $t("Reject") }}
                </button>
                <button
                    class="btn btn-success"
                    @click.prevent="submitAccept"
                    :disabled="loadingProcessAccept"
                    v-if="!reschedule"
                >
                    <b-spinner
                        v-if="loadingProcessAccept"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i
                        v-if="!loadingProcessAccept"
                        class="fa fa-check mr-2"
                    ></i>
                    {{ $t("Accept") }}
                </button>
                <button
                    class="btn btn-primary"
                    @click.prevent="submitAttend"
                    :disabled="loadingProcess"
                    v-if="reschedule"
                >
                    <b-spinner
                        v-if="loadingProcess"
                        small
                        class="mr-2"
                    ></b-spinner>
                    <i v-if="!loadingProcess" class="fa fa-check mr-2"></i>
                    {{ $t("Save") }}
                </button>
            </template>
        </b-modal>
    </div>
</template>
<script>
import { mapActions, mapState, mapMutations } from "vuex";
import formComponent from "./form.vue";
import formPersonal from "./personal.vue";
import formCreate from "./add.vue";
import formUpdate from "./edit.vue";
import formFinal from "./asessment.vue";
import vSelect from "vue-select";
export default {
    created() {
        this.load(this.tableParams);
        this.loadrecruitmentSteps();
    },
    data() {
        return {
            reschedule: false,
            acceptedStatus: false,
            acceptedDisabledStatus: false,
            nextForm: false,
            fields: [
                {
                    key: "index",
                    label: "#",
                    thStyle: "text-align: center; width: 35px;",
                    tdClass: "text-center"
                },
                {
                    key: "identity_card_number",
                    label: "Identity Card Number",
                    sortable: true
                },
                { key: "name", label: "Name", sortable: true },
                { key: "email", label: "Email", sortable: true },
                { key: "phone_number", label: "Phone Number", sortable: true },
                { key: "position", label: "Position", sortable: true },
                { key: "company", label: "Company", sortable: true },
                {
                    key: "recruitment_step",
                    label: "Recruitment Step",
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
            keyword: "",
            modelId: null,
            loadingProcess: false,
            loadingProcessAccept: false,
            recruitmentStepId: "",
            saveProcess: false,
            candidateID: null
        };
    },
    components: {
        formComponent,
        formCreate,
        formPersonal,
        formUpdate,
        formFinal,
        "v-select": vSelect
    },
    computed: {
        ...mapState(["errors"]),
        ...mapState("recruitment", {
            collection: state => state.collection,
            form: state => state.form,
            formFinal: state => state.formFinal
        }),
        ...mapState("notification", {
            notify: state => state.notification
        }),
        ...mapState("recruitment", {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState("recruitmentStep", {
            recruitmentSteps: state => state.collectionList
        }),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.humanResource.recruitment.tableParams;
            },
            set(params) {
                this.$store.commit(
                    "humanResource/recruitment/SET_TABLE_PARAMS",
                    params
                );
            }
        }
    },
    watch: {
        errors() {
            if (typeof this.errors === "object") {
                this.loadingProcess = false;
            }
        },
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
        recruitmentStepId() {
            let requestParams = {
                recruitmentStepId: this.recruitmentStepId,
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
            //this.modelId = null;
        });
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions("recruitment", [
            "load",
            "show",
            "showFinal",
            "reject",
            "accept",
            "attend",
            "store",
            "update",
            "updateCandidate",
            "destroy"
        ]),
        ...mapMutations("recruitment", ["CLEAR_FORM"]),
        ...mapActions("recruitmentStep", { loadrecruitmentSteps: "loadList" }),
        hideModal() {
            this.nextForm = false;
            this.loadingProcess = false;
            this.acceptedStatus = false;
            this.acceptedDisabledStatus = false;
            this.CLEAR_FORM();
            this.load(this.tableParams);
            this.$bvModal.hide("form-modal");
        },
        hideModalPersonal() {
            this.nextForm = false;
            this.loadingProcess = false;
            this.load(this.tableParams);
            this.$bvModal.hide("form-personal");
        },
        onChildClick(value) {
            this.reschedule = value;
        },
        changeAccepted(value) {
            this.acceptedStatus = value;
        },
        changeDisabledAccepted(value) {
            this.acceptedDisabledStatus = value;
        },
        hideModalCreate() {
            this.loadingProcess = false;
            this.saveProcess = true;
            this.load(this.tableParams);
            this.$bvModal.hide("form-create");
        },
        hideModalUpdate() {
            this.loadingProcess = false;
            this.load(this.tableParams);
            this.$bvModal.hide("form-update");
        },
        hideModalFinal() {
            this.loadingProcess = false;
            this.loadingProcessAccept = false;
            this.load(this.tableParams);
            this.$bvModal.hide("form-final");
        },
        view(id) {
            this.show(id).then(() => {
                this.$bvModal.show("form-modal");
            });
            this.modelId = id;
        },
        viewPersonal(id) {
            this.nextForm = true;
            console.log("Next", this.nextForm);
            this.$bvModal.hide("form-modal");
            this.$bvModal.show("form-personal");
            // console.log("id tes", id);
            this.modelId = id;
            this.candidateID = id;
        },
        viewBack(id) {
            this.nextForm = false;
            this.$bvModal.hide("form-personal");
            this.$bvModal.show("form-modal");
            // console.log("id tes", id);
            this.modelId = id;
            this.candidateID = id;
        },
        viewUpdate(id) {
            this.show(id);
            this.modelId = id;
            this.$bvModal.show("form-update");
        },
        viewFinal(id) {
            this.showFinal(id).then(() => {
                this.$bvModal.show("form-final");
            });
            this.modelId = id;
        },
        submitReject() {
            this.loadingProcess = true;

            this.reject(this.modelId).then(() => {
                this.hideModalFinal();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("recruitment"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },
        submitAccept() {
            this.loadingProcessAccept = true;
            this.accept(this.modelId).then(() => {
                this.hideModalFinal();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("recruitment"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },
        submitAttend() {
            this.loadingProcess = true;
            this.attend(this.modelId).then(() => {
                this.hideModalFinal();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("recruitment"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },

        submit() {
            this.loadingProcess = true;
            if (!this.modelId) {
                let form = new FormData();
                form.append(
                    `identity_card_number`,
                    this.form.identity_card_number
                );
                form.append(`company_id`, this.form.company_id);
                form.append(`position_id`, this.form.position_id);
                form.append(`name`, this.form.name);
                form.append(`email`, this.form.email);
                form.append(`phone_number`, this.form.phone_number);
                form.append(`birth_place`, this.form.birth_place);
                form.append(`birth_at`, this.form.birth_at);
                console.log("test", this.form.photo);
                form.append(`photo`, this.form.photo);
                form.append(`recruitment_date`, this.form.recruitment_date);
                form.append(
                    `recruitment_step_id`,
                    this.form.recruitment_step_id
                );

                // console.log("cik", this.form.files);
                this.form.files.forEach(function(file, key) {
                    console.log("caption", file.caption);
                    form.append(`dataUpload[${key}][caption]`, file.caption);
                    form.append(`dataUpload[${key}][note]`, file.note);
                });
                this.store(form).then(() => {
                    this.hideModalCreate();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("recruitment"),
                            message: this.$tc("add", 2)
                        })
                    });
                });
            } else {
                this.update(this.modelId).then(() => {
                    this.hideModal();
                    this.$message({
                        type: "success",
                        message: this.$t("present perfect", {
                            object: this.$tc("recruitment"),
                            message: this.$tc("update", 2)
                        })
                    });
                });
            }
        },
        submitCandidate() {
            this.loadingProcess = true;
            let form = new FormData();
            form.append(`candidate_id`, this.form.candidate_id);
            form.append(`identity_card_number`, this.form.identity_card_number);
            form.append(`company_id`, this.form.company_id);
            form.append(`position_id`, this.form.position_id);
            form.append(`name`, this.form.name);
            form.append(`email`, this.form.email);
            form.append(`old_attachment`, this.form.old_attachment);
            form.append(`phone_number`, this.form.phone_number);
            form.append(`recruitment_date`, this.form.recruitment_date);
            form.append(`recruitment_step_id`, this.form.recruitment_step_id);
            form.append(`photo`, this.form.photo);

            form.append("_method", "patch");
            this.updateCandidate({ id: this.modelId, form: form }).then(() => {
                this.hideModalUpdate();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("recruitment"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },
        submitAsessment() {
            this.loadingProcess = true;
            let form = new FormData();
            console.log(this.form);
            for (const key in this.form) {
                if (key != "candidate") {
                    if (this.form[key] != undefined || this.form[key] != null) {
                        form.append(key, this.form[key]);
                    }
                }
            }
            for (const key in this.form.candidate) {
                if (
                    this.form.candidate[key] != undefined ||
                    this.form.candidate[key] != null
                ) {
                    form.append(key, this.form.candidate[key]);
                }
            }
            form.append(`old_step`, this.form.old_step);
            //DATA RECRUITMENT
            form.append(
                `status_recruitment_id`,
                this.form.status_recruitment_id
            );
            form.append(`total_score`, this.form.total_score);
            form.append(`min_score`, this.form.recruitment_step.min_score);
            form.append(`newStatus`, this.form.newStatus);
            form.append(`recruitmentNote`, this.form.recruitmentNote);
            //DATA UPLOAD
            this.form.recruitment_files.forEach(function(file, key) {
                // console.log("Recruitment Files", file.caption);
                form.append(`upload[${key}][caption]`, file.caption);
                form.append(`upload[${key}][note]`, file.note);
            });
            //DATA CANDIDATE CHILDREN
            this.form.childrens.forEach(function(child, key) {
                console.log("dataChildren", child.name);
                form.append(`dataChildren[${key}][name]`, child.name);
                form.append(
                    `dataChildren[${key}][date_of_birth]`,
                    child.date_of_birth
                );
                form.append(`dataChildren[${key}][gender]`, child.gender);
            });
            //DATA SIBLING
            this.form.siblings.forEach(function(sibling, key) {
                // console.log("dataChildren", child.name);
                form.append(`dataSibling[${key}][name]`, sibling.name);
                form.append(
                    `dataSibling[${key}][date_of_birth]`,
                    sibling.date_of_birth
                );
                form.append(`dataSibling[${key}][gender]`, sibling.gender);
            });
            //DATA CANDIDATE PARENT
            this.form.parents.forEach(function(parent, key) {
                form.append(`dataParent[${key}][type]`, parent.type);
                form.append(`dataParent[${key}][name]`, parent.name);
                form.append(
                    `dataParent[${key}][date_of_birth]`,
                    parent.date_of_birth
                );
                form.append(`dataParent[${key}][gender]`, parent.gender);
                form.append(`dataParent[${key}][address]`, parent.address);
                form.append(`dataParent[${key}][phone]`, parent.phone);
                form.append(`dataParent[${key}][emergency]`, parent.emergency);
            });
            //DATA CANDIDATE TRAINING
            this.form.trainings.forEach(function(training, key) {
                // console.log("dataChildren", child.name);
                form.append(
                    `dataTraining[${key}][training_type]`,
                    training.training_type
                );
                form.append(
                    `dataTraining[${key}][organizer]`,
                    training.organizer
                );
                form.append(`dataTraining[${key}][year]`, training.year);
            });
            //DATA CANDIDATE JOB EXPERIENCE
            this.form.job_experiences.forEach(function(job_experience, key) {
                form.append(
                    `dataJobExperience[${key}][company_name]`,
                    job_experience.company_name
                );
                form.append(
                    `dataJobExperience[${key}][address]`,
                    job_experience.address
                );
                form.append(
                    `dataJobExperience[${key}][phone]`,
                    job_experience.phone
                );
                form.append(
                    `dataJobExperience[${key}][position]`,
                    job_experience.position
                );
                form.append(
                    `dataJobExperience[${key}][start_date]`,
                    job_experience.start_date
                );
                form.append(
                    `dataJobExperience[${key}][end_date]`,
                    job_experience.end_date
                );
            });
            //DATA CANDIDATE EDUCATION
            this.form.educations.forEach(function(education, key) {
                // console.log("dataChildren", child.name);
                console.log("major education", education.major);
                form.append(
                    `dataEducation[${key}][school_name]`,
                    education.school_name
                );
                form.append(`dataEducation[${key}][major]`, education.major);
                form.append(`dataEducation[${key}][city]`, education.city);
                form.append(
                    `dataEducation[${key}][entry_year]`,
                    education.entry_year
                );
                form.append(
                    `dataEducation[${key}][graduation_year]`,
                    education.graduation_year
                );
                form.append(`dataEducation[${key}][score]`, education.score);
            });
            //DATA CANDIDATE LANGUAGE
            this.form.languages.forEach(function(language, key) {
                // console.log("dataChildren", child.name);
                form.append(
                    `dataLanguage[${key}][language]`,
                    language.language
                );
                form.append(
                    `dataLanguage[${key}][speaking]`,
                    language.speaking
                );
                form.append(`dataLanguage[${key}][writing]`, language.writing);
                form.append(`dataLanguage[${key}][reading]`, language.reading);
            });
            //DATA CANDIDATE RELATIVE
            this.form.aquintances.forEach(function(aquintace, key) {
                // console.log("dataChildren", child.name);
                form.append(
                    `dataAquintance[${key}][relation]`,
                    aquintace.relation
                );
                form.append(
                    `dataAquintance[${key}][employee_id]`,
                    aquintace.employee_id
                );
            });
            //DATA CANDIDATE REFERENCE
            this.form.references.forEach(function(reference, key) {
                // console.log("dataChildren", child.name);
                form.append(`dataReference[${key}][name]`, reference.name);
                form.append(
                    `dataReference[${key}][address]`,
                    reference.address
                );
                form.append(`dataReference[${key}][phone]`, reference.phone);
                form.append(`dataReference[${key}][job]`, reference.job);
            });

            //DATA SCORE RECRUITMENT
            let recruitment_step_parameter = this.form.recruitment_step
                .recruitment_step_parameter;
            recruitment_step_parameter.forEach(function(item, i) {
                // console.log("Score", item.score);
                form.append(
                    `recruitment_step_parameter[${i}][recruitment_step_parameter_id]`,
                    item.id
                );
                form.append(
                    `recruitment_step_parameter[${i}][score_id]`,
                    item.score
                );
                form.append(
                    `recruitment_step_parameter[${i}][note]`,
                    item.note
                );
            });

            form.append("_method", "patch");
            this.update({ id: this.modelId, form: form }).then(() => {
                this.hideModal();
                this.hideModalPersonal();
                this.CLEAR_FORM();
                this.$message({
                    type: "success",
                    message: this.$t("present perfect", {
                        object: this.$tc("recruitment"),
                        message: this.$tc("update", 2)
                    })
                });
            });
        },
        remove(item) {
            this.$confirm(
                this.$t("delete confirmation", {
                    item: item.name,
                    module: this.$tc("recruitment")
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
        },
        cancelCreate() {
            this.$confirm(
                this.$t("cancel confirmation", {
                    // item: item.name,

                    module: this.$tc("recruitment")
                }),
                this.$t("warning"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    // this.destroy(item.id);
                    this.loadingProcess = false;
                    this.saveProcess = true;
                    this.load(this.tableParams);
                    this.$bvModal.hide("form-create");
                    this.$message({
                        type: "success",
                        message: this.$t("cancel successfully")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("not canceled")
                    });
                });
        },
        cancelAsessment() {
            this.$confirm(
                this.$t("cancel confirmation", {
                    // item: item.name,

                    module: this.$tc("recruitment")
                }),
                this.$t("warning"),
                {
                    confirmButtonText: this.$t("ok"),
                    cancelButtonText: this.$t("cancel"),
                    type: "warning"
                }
            )
                .then(() => {
                    this.nextForm = false;
                    this.loadingProcess = false;
                    this.acceptedStatus = false;
                    this.acceptedDisabledStatus = false;
                    this.CLEAR_FORM();
                    this.load(this.tableParams);
                    this.$bvModal.hide("form-modal");
                    this.$bvModal.hide("form-personal");
                    this.$message({
                        type: "success",
                        message: this.$t("cancel successfully")
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: this.$t("not canceled")
                    });
                });
        },
        exportFile(type) {
            window.open(
                `/api/human-resource/recruitment-export-${type}?api_token=${this.$store.state.token}`
            );
        }
    }
};
</script>
