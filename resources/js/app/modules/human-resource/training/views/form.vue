<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.training_type_id }">
            <label for="">{{ $tc('Training Type') }}</label>
            <v-select v-model="form.training_type_id" :options="trainingTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.training_type_id }"></v-select>
            <p class="text-danger" v-if="errors.training_type_id">{{ errors.training_type_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('Training Name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
            <div class="form-group" :class="{ 'has-error': errors.training_date }">
                <label for="">{{ $tc('Training Date') }}</label>
                <date-picker :class="{ 'border-danger': errors.training_date }" v-model="form.training_date"
                        format="YYYY-MM-DD"
                        value-type="format"
                        type="date"/>
                <p class="text-danger" v-if="errors.training_date">{{ errors.training_date[0] }}</p>
            </div>
                    <!-- {{ form.join_at }} -->
            <div class="form-group" :class="{ 'has-error': errors.employee_id }">
                <label for="">{{ $tc('Employee') }}</label>
                <v-select multiple v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
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
        this.loadTrainingTypes();
        this.loadEmployees();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('training', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('trainingType', {
            trainingTypes: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('training', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('trainingType', {loadTrainingTypes:'loadList'}),
        ...mapActions('employee', {loadEmployees:'loadList'})
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
