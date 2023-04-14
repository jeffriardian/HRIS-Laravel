<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc('Employee Name') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.date_of_incident }">
            <label for="">{{ $tc('Date of Incident') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.date_of_incident" format="dd-MM-yyyy">
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
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.lost_cost }">
            <label for="">{{ $t('Lost Cost') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.lost_cost }" v-model="form.lost_cost">
            <p class="text-danger" v-if="errors.lost_cost">{{ errors.lost_cost[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.penalty }">
            <label for="">{{ $t('Penalty') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.penalty }" v-model="form.penalty">
            <p class="text-danger" v-if="errors.penalty">{{ errors.penalty[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.installment_count }">
            <label for="">{{ $t('Installment Count') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.installment_count }" v-model="form.installment_count">
            <p class="text-danger" v-if="errors.installment_count">{{ errors.installment_count[0] }}</p>
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
        this.loadEmployees();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('incidentPenalty', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('incidentPenalty', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
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
