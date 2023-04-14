<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.employee_id }">
                <label for="">{{ $tc('Employee Name') }}</label>
                <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.employee_status_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.employee_status_id }">
                <label for="">{{ $tc('Employee Status') }}</label>
                <v-select v-model="form.employee_status_id" :options="employeeStatuses.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_status_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_status_id">{{ errors.employee_status_id[0] }}</p>
            </div>
            <!-- {{ form.employee_status_id }} -->
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_date }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.employee_status_id }">
                <label for="">{{ $tc('Start Date') }}</label>
                <!-- buat datepicker disini -->
                <datetime type = "date" v-model="form.start_date" format="dd-MM-yyyy" zone="local" value-zone="local">
                    <template slot="button-cancel">
                        <fa :icon="['far', 'times']"></fa>
                            Cancel
                        </template>
                        <template slot="button-confirm">
                            <fa :icon="['fas', 'check-circle']"></fa>
                            Confirm
                    </template>
                </datetime>
            </div>
        </div>
        <!-- <div class="form-group" :class="{ 'has-error': errors.end_date }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.employee_status_id }">
                <label for="">{{ $tc('End Date') }}</label>
                <datetime type = "date" v-model="form.end_date" format="dd-MM-yyyy" zone="local" value-zone="local">
                    <template slot="button-cancel">
                        <fa :icon="['far', 'times']"></fa>
                            Cancel
                        </template>
                        <template slot="button-confirm">
                            <fa :icon="['fas', 'check-circle']"></fa>
                            Confirm
                    </template>
                </datetime>
            </div>
        </div> -->
        <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div> -->
    </div>
</template>

<script>

import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import moment from 'moment'
import 'vue-datetime/dist/vue-datetime.css'
import 'vue-datetime/dist/vue-datetime.js'
import vSelect from 'vue-select'
import { mapState, mapMutations, mapActions } from 'vuex'

export default {
    created(){
        this.loadEmployees();
        this.loadEmployeeStatuses();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('historyEmployeeStatus', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('employeeStatus', {
            employeeStatuses: state => state.collectionList,
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('historyEmployeeStatus', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('employeeStatus', {loadEmployeeStatuses:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        'v-select': vSelect,
        datetime: Datetime,
    }
}
</script>
