<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.payroll_component_type_id }">
            <label for="">{{ $tc('Payroll Component Type') }}</label>
            <v-select v-model="form.payroll_component_type_id" :options="payrollComponentTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.payroll_component_type_id }"></v-select>
            <p class="text-danger" v-if="errors.payroll_component_type_id">{{ errors.payroll_component_type_id[0] }}</p>
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
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('payrollComponentType', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('payrollComponentType', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
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
