<template>
    <div>
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
        this.addDetail();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('attendance', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('attendance', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addDetail() {
            this.form.details.push({ in: null, out: null, weekend_in: null, weekend_out: null});
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removeDetail(index) {
            if (this.form.details.length > 1) {
                this.form.details.splice(index, 1)
            }
        },
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
