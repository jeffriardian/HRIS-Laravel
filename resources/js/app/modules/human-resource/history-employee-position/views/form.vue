<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.employee_id }">
                <label for="">{{ $tc('Employee Name') }}</label>
                <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.department_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.department_id }">
                <label for="">{{ $tc('Department') }}</label>
                <v-select v-model="form.department_id" :options="departments.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.department_id }"></v-select>
                <p class="text-danger" v-if="errors.department_id">{{ errors.department_id[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.position_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.position_id }">
                <label for="">{{ $tc('Department') }}</label>
                <v-select v-model="form.position_id" :options="positions.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.position_id }"></v-select>
                <p class="text-danger" v-if="errors.position_id">{{ errors.position_id[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_date }">
                <label for="">{{ $tc('Start Date') }}</label>
                <date-picker :class="{ 'border-danger': errors.start_date }" v-model="form.start_date"
                        format="YYYY-MM-DD"
                        value-type="format"
                        type="date"/>
                <p class="text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</p>
            </div>
        <div class="form-group" :class="{ 'has-error': errors.file }">
                    <label for="">{{ $t('File') }}</label>
                    <input type="file" class="form-control" @change="uploadPhoto($event)" :class="{ 'border-danger': errors.file }">
                    <p class="text-danger" v-if="errors.file">{{ errors.file[0] }}</p>
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
    created(){
        this.loadEmployees();
        this.loadDepartments();
        this.loadPositions();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('historyEmployeePosition', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('department', {
            departments: state => state.collectionList,
        }),
        ...mapState('position', {
            positions: state => state.collectionList,
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('historyEmployeePosition', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('department', {loadDepartments:'loadList'}),
        ...mapActions('position', {loadPositions:'loadList'}),
        ...mapActions("historyEmployeePosition", ["store"]), //PANGGIL ACTIONS store
         uploadPhoto(event){
            console.log(event);
            this.form.file = event.target.files[0];
            console.log(this.form.file)
        },
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
