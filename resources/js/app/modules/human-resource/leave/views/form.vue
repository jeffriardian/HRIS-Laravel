<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.leave_category_id }">
            <label for="">{{ $tc('Leave Category') }}</label>
            <v-select v-model="form.leave_category_id" :options="leaveCategories.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.leave_category_id }"></v-select>
            <p class="text-danger" v-if="errors.leave_category_id">{{ errors.leave_category_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc('Employee Name') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_date }">
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
        <div class="form-group" :class="{ 'has-error': errors.total }">
            <label for="">{{ $t('Total') }}</label>
            <input type="text" class="form-control" @change="getEndDate()" :class="{ 'border-danger': errors.total }" v-model="form.total">
            <p class="text-danger" v-if="errors.total">{{ errors.total[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.end_date }">
            <label for="">{{ $t('End Date') }}</label>
            <input type="end_date" class="form-control" :class="{ 'border-danger': errors.end_date }" v-model="form.end_date">
            <p class="text-danger" v-if="errors.end_date">{{ errors.end_date[0] }}</p>
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
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
        this.loadleaveCategories();
        this.loadEmployees();
        this.getEndDate();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('leave', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('leaveCategory', {
            leaveCategories: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('leave', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('leaveCategory', {loadleaveCategories:'loadList'}),
        ...mapActions('employee', {loadEmployees:'loadList'}),

        getEndDate(){
            var startDate = moment(this.form.start_date).format('YYYY-MM-DD');
            var endDate = moment(startDate).add('days', (this.form.total)-1).format('DD-MM-YYYY');
            this.form.end_date = endDate;
        }
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
