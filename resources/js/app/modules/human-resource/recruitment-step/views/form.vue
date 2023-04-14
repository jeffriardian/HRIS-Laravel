<template>
    <div>
        <div class="form-row">
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.name }"
            >
                <label for="">{{ $t("name") }}</label>
                <input
                    type="text"
                    class="form-control"
                    :class="{ 'border-danger': errors.name }"
                    v-model="form.name"
                />
                <p class="text-danger" v-if="errors.name">
                    {{ errors.name[0] }}
                </p>
            </div>
            <div
                class="form-group col-md-6"
                :class="{ 'has-error': errors.min_score }"
            >
                <label for="">{{ $t("Minimal Score") }}</label>
                <input
                    type="number"
                    class="form-control"
                    :class="{ 'border-danger': errors.min_score }"
                    v-model="form.min_score"
                />
                <p class="text-danger" v-if="errors.min_score">
                    {{ errors.min_score[0] }}
                </p>
            </div>
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
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h5 class="card-title">{{ $tc("Parameter", 2) }}</h5>
                    </div>
                    <div class="col-3">
                        <button
                            type="button"
                            @click="addParameter()"
                            class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right"
                            v-b-tooltip.hover
                            :title="$tc('add')"
                        >
                            <i class="mdi mdi-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
                <thead>
                    <tr>
                        <th width="25%">{{ $t("name") }}</th>
                        <th>{{ $t("description") }}</th>
                        <th width="70px">{{ $t("action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in form.parameters"
                        :key="index"
                        class="justify-content-between align-items-center"
                    >
                        <td>
                            <input
                                type="text"
                                class="form-control form-control"
                                :class="{
                                    'border-danger': errors[`${index}.name`]
                                }"
                                v-model="row.name"
                            />
                            <p
                                class="text-danger"
                                v-if="errors[`${index}.name`]"
                            >
                                {{ errors[`${index}.name`][0] }}
                            </p>
                            <br />
                        </td>
                        <td>
                            <textarea
                                class="form-control form-control"
                                :class="{
                                    'border-danger':
                                        errors[`${index}.description`]
                                }"
                                v-model="row.description"
                            ></textarea>
                            <p
                                class="text-danger"
                                v-if="errors[`parameters.${index}.description`]"
                            >
                                {{
                                    errors[`parameters.${index}.description`][0]
                                }}
                            </p>
                        </td>
                        <td class="text-cenxter">
                            <a
                                href="javascript:void(0)"
                                @click="removeParameter(index)"
                                class="text-danger"
                                v-b-tooltip.hover
                                :title="$tc('delete')"
                            >
                                <i
                                    class="mdi mdi-trash-can-outline font-size-16"
                                ></i>
                            </a>
                            <a v-b-modal="`my-modal-${row['id']}`"
                                ><i
                                    class="mdi mdi-information font-size-16 text-primary"
                                ></i
                            ></a>
                            <b-modal
                                :id="`my-modal-${row['id']}`"
                                title="Information"
                            >
                                <formScore :parameter="row"></formScore>
                            </b-modal>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
export default {
    props: ["modelId"],
    computed: {
        ...mapState(["errors"]), //MENGAMBIL STATE ERRORS
        ...mapState("recruitmentStep", {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(["CLEAR_ERRORS"]),
        ...mapMutations("recruitmentStep", ["CLEAR_FORM"]), //PANGGIL MUTATIONS CLEAR_FORM
        addParameter() {
            console.log(this.form.parameters);
            this.form.parameters.push({ name: null, description: null });
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removeParameter(index) {
            if (this.form.parameters.length > 1) {
                this.form.parameters.splice(index, 1);
            }
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
};
</script>
