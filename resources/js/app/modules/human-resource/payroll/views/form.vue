<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.company_id }">
            <label for="">{{ $tc('Company') }}</label>
            <v-select v-model="form.company_id" :options="companies.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.company_id }"></v-select>
            <p class="text-danger" v-if="errors.company_id">{{ errors.company_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_period }">
            <label for="">{{ $tc('Start Period') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.start_period" format="dd-MM-yyyy" zone="local" value-zone="local">
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
        <div class="form-group" :class="{ 'has-error': errors.end_period }">
            <label for="">{{ $tc('End Period') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.end_period" format="dd-MM-yyyy" zone="local" value-zone="local">
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
        <div class="form-group" :class="{ 'has-error': errors.date_created }">
            <label for="">{{ $tc('Date Created') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.date_created" format="dd-MM-yyyy" zone="local" value-zone="local">
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
</template>

<script>

import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import moment from 'moment'
import 'vue-datetime/dist/vue-datetime.css'
import 'vue-datetime/dist/vue-datetime.js'

import vSelect from 'vue-select'
import { mapState, mapMutations, mapActions } from 'vuex'
import { BFormFile } from 'bootstrap-vue'

export default {
    created(){
        this.loadCompanies();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('payroll', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('company', {
            companies: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('payroll', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('company', {loadCompanies:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        'b-form-file':BFormFile,
        'v-select': vSelect,
        datetime: Datetime,

    }
}
</script>
