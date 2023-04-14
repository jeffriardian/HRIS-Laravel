<template>
    <div>
        <!-- <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">{{ $t('code') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code" :readonly="this.modelId != null">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div> -->
        <div class="form-row">
            <div class="form-group col-md-4" :class="{ 'has-error': errors.work_order_id }">
                <label for="">{{ $tc('work order') }}</label>
                <v-select @input="setWorkOrderSelected" v-model="form.work_order_id" :options="workOrders.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors.work_order_id }"></v-select>
                <p class="text-danger" v-if="errors.work_order_id">{{ errors.work_order_id[0] }}</p>
            </div>
            <div class="form-group col-md-4" :class="{ 'has-error': errors.machine_id }">
                <label for="">{{ $tc('machine') }}</label>
                <v-select v-model="form.machine_id" :options="machines.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors.machine_id }">
                    <template #option="{code, area, machine_group, machine_type}">
                            {{ area }} - {{ code }} <br>
                            <small>{{ machine_group ? machine_group.name : ''}} | {{ machine_type ? machine_type.name : ''}}</small>
                    </template>
                </v-select>
                <p class="text-danger" v-if="errors.machine_id">{{ errors.machine_id[0] }}</p>
            </div>
            <div class="form-group col-md-4" :class="{ 'has-error': errors.employee_id }">
                <label for="">{{ $tc('employee') }}</label>
                <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="">{{ $tc('stop at') }}</label>
                <br />
                <date-picker :class="{ 'border-danger': errors.stop_at }" v-model="form.stop_at"
                    :time-picker-options="{
                        start: '07:00',
                        step: '00:30',
                        end: '17:00',
                    }"
                    format="HH:mm:ss"
                    value-type="format"
                    type="time"/>
                <p class="text-danger" v-if="errors.stop_at">{{ errors.stop_at[0] }}</p>
            </div>
            <div class="form-group col-md-4">
                <label for="">{{ $tc('start at') }}</label>
                <br />
                <date-picker :class="{ 'border-danger': errors.start_at }" v-model="form.start_at"
                    :time-picker-options="{
                        start: '07:00',
                        step: '00:30',
                        end: '17:00',
                    }"
                    format="HH:mm:ss"
                    value-type="format"
                    type="time"/>
                <p class="text-danger" v-if="errors.start_at">{{ errors.start_at[0] }}</p>
            </div>
            <div class="form-group col-md-4">
                <label for="">{{ $tc('duration') }}</label>
                <b-form-input size="sm" class="input-duration" v-model="rangeDuration" disabled></b-form-input>
                <small class="text-muted float-right">Duration auto calculate between stop and start, value return in hours</small>
            </div>
        </div>
        <div v-if="!this.workOrder || this.workOrder.work_order_action_id != 2" class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" :value="workOrder.description" readonly></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div v-else>
            <b-table-simple bordered hover small>
                <b-thead head-variant="light">
                    <b-tr>
                        <b-th rowspan="2" class="text-center align-middle" scope="col">#</b-th>
                        <b-th rowspan="2" class="align-middle" scope="col">{{ $tc('part') }}</b-th>
                        <b-th colspan="2" class="text-center align-middle">Cleaning</b-th>
                        <b-th colspan="2" class="text-center align-middle">Tightening</b-th>
                        <b-th colspan="2" class="text-center align-middle">Lubricating</b-th>
                    </b-tr>
                    <b-tr>
                        <b-th class="text-center align-middle" width='135px'>On Position</b-th>
                        <b-th class="text-center align-middle" width='135px'>Remove & Install</b-th>
                        <b-th class="text-center align-middle" width='135px'>Setting & Adjustment</b-th>
                        <b-th class="text-center align-middle" width='135px'>Replace</b-th>
                        <b-th class="text-center align-middle" width='135px'>Oil</b-th>
                        <b-th class="text-center align-middle" width='135px'>Grease</b-th>
                    </b-tr>
                </b-thead>
                <b-tbody>
                    <b-tr v-for="(part, index) in this.workOrder.machine_group.parts" :key="index">
                        <b-td class="text-center">{{ index+1 }}</b-td>
                        <b-td>{{ part ? part.name : '' }}</b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                        <b-td class="text-center align-middle"><b-form-checkbox/></b-td>
                    </b-tr>
                </b-tbody>
            </b-table-simple>
        </div>

        <div class="card m-b-30">
            <div class="card-header bg-dark">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h5 class="card-title text-light">Part Order</h5>
                    </div>
                    <div class="col-3">
                        <button type="button" @click="addPartOrder()" class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right" v-b-tooltip.hover :title="$tc('add')"><i class="mdi mdi-plus"></i></button>
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
                    <tr v-for="(row, index) in form.part_orders" :key="index" class="justify-content-between align-items-center">
                        <td>
                            <input type="text" class="form-control form-control-sm" :class="{ 'border-danger': errors[`partOrders.${index}.part`] }" v-model="row.part">
                            <p class="text-danger" v-if="errors[`partOrders.${index}.part`]">{{ errors[`partOrders.${index}.part`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`partOrders.${index}.quantity`] }" v-model="row.quantity">
                            <p class="text-danger" v-if="errors[`partOrders.${index}.quantity`]">{{ errors[`partOrders.${index}.quantity`][0] }}</p>
                        </td>
                        <td class="text-center">
                            <a href='javascript:void(0)' @click="removePartOrder(index)" class="text-danger" v-b-tooltip.hover :title="$tc('delete')"><i class="mdi mdi-trash-can-outline font-size-16"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div> -->
    </div>
</template>

<script>
import moment from 'moment'
import vSelect from 'vue-select'
import { mapState, mapActions, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    components: {
        vSelect
    },
    data() {
        return {
            workOrder:[]
        }
    },
    created(){
        this.loadWorkOrders();
        this.loadEmployees();
        this.loadMachines();
        this.addPartOrder();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('jobCard', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('workOrder', {
            workOrders: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('machine', {
            machines: state => state.collectionList,
        }),
        rangeDuration() {
            var stop_at = moment(this.form.stop_at,'HH:mm'); //todays date
            var start_at = moment(this.form.start_at,'HH:mm'); // another date
            var duration = moment.duration(start_at.diff(stop_at))

            return (this.form.stop_at && this.form.start_at) ? duration.as('hours') : '';
        },
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('jobCard', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('workOrder', {loadWorkOrders:'loadList'}),
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('machine', {loadMachines:'loadList'}),
        setWorkOrderSelected(value) {
            const dataSelected = _.filter(this.workOrders.data, { 'id': value });
            
            this.workOrder = (dataSelected[0]);
        },
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addPartOrder() {
            this.form.part_orders.push({ part: null, quantity: null});
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removePartOrder(index) {
            if (this.form.part_orders.length > 1) {
                this.form.part_orders.splice(index, 1)
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
<style>
.mx-input{
    height: calc(1em + 0.94rem + 2px) !important;
}

.input-duration{
    height: calc(1.2em + 0.94rem + 2px) !important;
}
</style>