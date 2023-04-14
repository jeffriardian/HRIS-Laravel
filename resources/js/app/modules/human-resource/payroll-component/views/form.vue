<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.payroll_component_type_id }">
            <label for="">{{ $tc('Component Type') }}</label>
            <v-select v-model="form.payroll_component_type_id" :options="payrollComponentTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.payroll_component_type_id }"></v-select>
            <p class="text-danger" v-if="errors.payroll_component_type_id">{{ errors.payroll_component_type_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.status }">
            <label for="">{{ $tc('Component Status') }}</label>
            <!-- <b-form-group class="col-md-12" :label="$t('Component Status')"> -->
                <b-form-radio-group
                    v-model="form.status"
                    :options="statusOptions"
                ></b-form-radio-group>
            <!-- </b-form-group> -->
        </div>
        <div class="form-group" :class="{ 'has-error': errors.time }">
            <label for="">{{ $tc('Component Type Time') }}</label>
            <!-- <b-form-group class="col-md-12" :label="$t('Component Type Time')"> -->
                <b-form-radio-group
                    v-model="form.time"
                    :options="timeOptions"
                ></b-form-radio-group>
            <!-- </b-form-group> -->
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.taxable" value=1 unchecked-value=0>
                {{ $t('Taxable') }}
            </b-form-checkbox>
        </div>
        <div class="form-group">
            <label for="">{{ $tc('Effective Date') }}</label>
                <!-- buat datepicker disini -->
                <datetime type = "date" v-model="form.effective_date" format="dd-MM-yyyy" zone="local" value-zone="local">
                    <template slot="button-cancel">
                        <fa :icon="['far', 'times']"></fa>
                            Cancel
                    </template>
                    <template slot="button-confirm">
                        <fa :icon="['fas', 'check-circle']"></fa>
                            Confirm
                    </template>
                </datetime>
                <!-- {{ form.join_at }} -->
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
    data() {
        return {
            statusOptions: [
                { text: this.$tc('Income'), value: 'Income'},
                { text: this.$tc('Deduction'), value: 'Deduction'},
            ],
            timeOptions: [
                { text: this.$tc('Monthly'), value: 'Monthly'},
                { text: this.$tc('Daily'), value: 'Daily'},
                { text: this.$tc('One Time'), value: 'One Time'},
            ],
        }
    },
    props: [
        'modelId'
    ],
    created(){
        this.loadpayrollComponentTypes();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('payrollComponent', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('payrollComponentType', {
            payrollComponentTypes: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('payrollComponent', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('payrollComponentType', {loadpayrollComponentTypes:'loadList'}),
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
