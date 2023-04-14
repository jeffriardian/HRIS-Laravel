<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.holiday_date }">
            <label for="">{{ $tc('Date of Holiday') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.holiday_date" format="dd-MM-yyyy" zone="local" value-zone="local">
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
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('publicHoliday', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('publicHoliday', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
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
