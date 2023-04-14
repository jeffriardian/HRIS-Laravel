<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.job_card_id }">
                <label for="">{{ $tc('job card') }}</label>
                <v-select v-model="form.job_card_id" :options="jobCards.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors.job_card_id }">
                    <template #option="{employee, work_order}">
                        {{ work_order.code }} - {{ employee.name }} <br>
                    </template>
                </v-select>
                <p class="text-danger" v-if="errors.job_card_id">{{ errors.job_card_id[0] }}</p>
            </div>
            <div class="form-group col-md-3" :class="{ 'has-error': errors.machine_id }">
                <label for="">{{ $tc('machine') }}</label>
                <v-select v-model="form.machine_id" :options="machines.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors.machine_id }">
                    <template #option="{code, area, machine_group, machine_type}">
                            {{ area }} - {{ code }} <br>
                            <small>{{ machine_group ? machine_group.name : ''}} | {{ machine_type ? machine_type.name : ''}}</small>
                    </template>
                </v-select>
                <p class="text-danger" v-if="errors.machine_id">{{ errors.machine_id[0] }}</p>
            </div>
            <div class="form-group col-md-3" :class="{ 'has-error': errors.failure_type_id }">
                <label for="">{{ $tc('failure type') }}</label>
                <v-select v-model="form.failure_type_id" :options="failureTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.failure_type_id }"></v-select>
                <p class="text-danger" v-if="errors.failure_type_id">{{ errors.failure_type_id[0] }}</p>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.estimation_repair_time }">
                <label for="">{{ $t('estimation repair time') }}</label>
                <input type="text" class="form-control form-control-sm" :class="{ 'border-danger': errors.estimation_repair_time }" v-model="form.estimation_repair_time">
                <p class="text-danger" v-if="errors.estimation_repair_time">{{ errors.estimation_repair_time[0] }}</p>
                <small class="text-muted">time to fix the problem (/hours)</small>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.estimation_endurance_time }">
                <label for="">{{ $t('estimation endurance time') }}</label>
                <input type="text" class="form-control form-control-sm" :class="{ 'border-danger': errors.estimation_endurance_time }" v-model="form.estimation_endurance_time">
                <p class="text-danger" v-if="errors.estimation_endurance_time">{{ errors.estimation_endurance_time[0] }}</p>
                <small class="text-muted">time remaining the machine can survice with this problem (/hours)</small>
            </div>
        </div>

        <div class="card m-b-30">
            <div class="card-header bg-dark">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h5 class="card-title text-light">Part</h5>
                    </div>
                    <div class="col-3">
                        <button type="button" @click="addPart()" class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right" v-b-tooltip.hover :title="$tc('add')"><i class="mdi mdi-plus"></i></button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
                <thead>
                    <tr>
                        <th width="250px">{{ $tc('part') }}</th>
                        <th width="250px">{{ $tc('quantity') }}</th>
                        <th width="70px">{{ $t('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in form.parts" :key="index" class="justify-content-between align-items-center">
                        <td>
                            <input type="text" class="form-control form-control-sm" :class="{ 'border-danger': errors[`parts.${index}.part`] }" v-model="row.part">
                            <p class="text-danger" v-if="errors[`parts.${index}.part`]">{{ errors[`parts.${index}.part`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`parts.${index}.quantity`] }" v-model="row.quantity">
                            <p class="text-danger" v-if="errors[`parts.${index}.quantity`]">{{ errors[`parts.${index}.quantity`][0] }}</p>
                        </td>
                        <td class="text-center">
                            <a href='javascript:void(0)' @click="removePart(index)" class="text-danger" v-b-tooltip.hover :title="$tc('delete')"><i class="mdi mdi-trash-can-outline font-size-16"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'
import { mapState, mapActions, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    components: {
        vSelect
    },
    created(){
        this.loadJobCards();
        this.loadMachines();
        this.loadFailureTypes();
        this.addPart();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('backlog', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('jobCard', {
            jobCards: state => state.collectionList,
        }),
        ...mapState('machine', {
            machines: state => state.collectionList,
        }),
        ...mapState('failureType', {
            failureTypes: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('backlog', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('jobCard', {loadJobCards:'loadList'}),
        ...mapActions('machine', {loadMachines:'loadList'}),
        ...mapActions('failureType', {loadFailureTypes:'loadList'}),
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addPart() {
            this.form.parts.push({ part: null, quantity: null});
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removePart(index) {
            if (this.form.parts.length > 1) {
                this.form.parts.splice(index, 1)
            }
        },
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>