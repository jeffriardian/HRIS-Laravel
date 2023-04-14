<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc('Employee Name') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.payroll_component_id }">
            <label for="">{{ $tc('Component Name') }}</label>
            <v-select v-model="form.payroll_component_id" :options="payrollComponents.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.payroll_component_id }"></v-select>
            <p class="text-danger" v-if="errors.payroll_component_id">{{ errors.payroll_component_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.amount }">
            <label for="">{{ $t('Amount') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.amount }" v-model="form.amount">
            <p class="text-danger" v-if="errors.amount">{{ errors.amount[0] }}</p>
        </div>
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
    props: [
        'modelId'
    ],
    created(){
        this.loadEmployees();
        this.loadPayrollComponents();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('salary', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('payrollComponent', {
            payrollComponents: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('salary', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('payrollComponent', {loadPayrollComponents:'loadList'}),
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
