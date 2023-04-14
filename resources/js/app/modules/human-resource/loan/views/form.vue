<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.cooperative_member_id }">
            <label for="">{{ $tc('Cooperative Member') }}</label>
            <v-select v-model="form.cooperative_member_id" :options="cooperativeMembers.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.cooperative_member_id }"></v-select>
            <p class="text-danger" v-if="errors.cooperative_member_id">{{ errors.cooperative_member_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.loan_date }">
            <label for="">{{ $tc('Loan Date') }}</label>
                <!-- buat datepicker disini -->
                <datetime type = "date" v-model="form.loan_date" format="dd-MM-yyyy">
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
        <div class="form-group" :class="{ 'has-error': errors.amount }">
            <label for="">{{ $t('Amount') }}</label>
            <input type="text" class="form-control" @input="changeTotal()" :class="{ 'border-danger': errors.amount }" v-model="form.amount">
            <p class="text-danger" v-if="errors.amount">{{ errors.amount[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.interest }">
            <label for="">{{ $t('Interest (%)') }}</label>
            <input type="text" class="form-control" @input="changeTotal()" :class="{ 'border-danger': errors.interest }" v-model="form.interest">
            <p class="text-danger" v-if="errors.interest">{{ errors.interest[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.total }">
            <label for="">{{ $t('Total') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.total }" v-model="form.total" disabled>
            <p class="text-danger" v-if="errors.total">{{ errors.total[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.installment_count }">
            <label for="">{{ $t('Installment Count') }}</label>
            <input type="text" class="form-control" @input="changeTotal()" :class="{ 'border-danger': errors.installment_count }" v-model="form.installment_count">
            <p class="text-danger" v-if="errors.installment_count">{{ errors.installment_count[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.installment_payment }">
            <label for="">{{ $t('Installment Payment') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.installment_payment }" v-model="form.installment_payment" disabled>
            <p class="text-danger" v-if="errors.installment_payment">{{ errors.installment_payment[0] }}</p>
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
    created(){
        this.loadCooperativeMembers();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('loan', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('cooperativeMember', {
            cooperativeMembers: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('loan', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('cooperativeMember', {loadCooperativeMembers:'loadList'}),

        changeTotal(){
            var amount = parseFloat(this.form.amount);
            var interest = ((parseFloat(this.form.interest))/100)*parseFloat(this.form.amount);
            var count = parseFloat(this.form.installment_count);
            var total = amount+interest;

            this.form.total = total;

            if (this.form.installment_count != '0'){
                this.form.installment_payment = total/count;
            }else{
                this.form.installment_payment = total;
            }
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
