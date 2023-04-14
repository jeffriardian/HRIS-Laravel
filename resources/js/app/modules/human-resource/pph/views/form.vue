<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.month_id }">
            <label for="">{{ $tc('Month') }}</label>
            <v-select v-model="form.month_id" :options="months.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.month_id }"></v-select>
            <p class="text-danger" v-if="errors.month_id">{{ errors.month_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.year_id }">
            <label for="">{{ $tc('Year') }}</label>
            <v-select v-model="form.year_id" :options="years.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.year_id }"></v-select>
            <p class="text-danger" v-if="errors.year_id">{{ errors.year_id[0] }}</p>
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
        this.loadMonths();
        this.loadYears();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('pph', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('month', {
            months: state => state.collectionList,
        }),
        ...mapState('year', {
            years: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('pph', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('month', {loadMonths:'loadList'}),
        ...mapActions('year', {loadYears:'loadList'}),
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
