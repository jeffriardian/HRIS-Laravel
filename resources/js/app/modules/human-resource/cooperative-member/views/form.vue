<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.cooperative_member_type_id }">
            <label for="">{{ $tc('Member Cooperative Type') }}</label>
            <v-select v-model="form.cooperative_member_type_id" :options="cooperativeMemberTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.cooperative_member_type_id}"></v-select>
            <p class="text-danger" v-if="errors.cooperative_member_type_id">{{ errors.cooperative_member_type_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.company_id }">
            <label for="">{{ $tc('Company') }}</label>
            <v-select v-model="form.company_id" :options="companies.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.company_id }"></v-select>
            <p class="text-danger" v-if="errors.company_id">{{ errors.company_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }" v-if="form.company_id == '1' || form.company_id == '2'">
            <label for="">{{ $tc('Employee') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.txtemployee_id }"  v-if="form.company_id == '5' || form.company_id == '6'">
            <label for="">{{ $t('Employee Name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.txtemployee_id }" v-model="form.txtemployee_id">
            <p class="text-danger" v-if="errors.txtemployee_id">{{ errors.txtemployee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.join_date }">
            <label for="">{{ $tc('Join Date') }}</label>
                <!-- buat datepicker disini -->
                <datetime type = "date" v-model="form.join_date" format="dd-MM-yyyy">
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
        <!-- <div class="form-group" :class="{ 'has-error': errors.saving_balance }">
            <label for="">{{ $t('Saving Balance') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.saving_balance }" v-model="form.saving_balance">
            <p class="text-danger" v-if="errors.saving_balance">{{ errors.saving_balance[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.loan_balance }">
            <label for="">{{ $t('Loan Balance') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.loan_balance }" v-model="form.loan_balance">
            <p class="text-danger" v-if="errors.loan_balance">{{ errors.loan_balance[0] }}</p>
        </div>
        <div class="form-group" @input="changeTotal()" :class="{ 'has-error': errors.installment_count }">
            <label for="">{{ $t('Installment Count') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.installment_count }" v-model="form.installment_count">
            <p class="text-danger" v-if="errors.installment_count">{{ errors.installment_count[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.installment_payment }">
            <label for="">{{ $t('Installment Payment') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.installment_payment }" v-model="form.installment_payment">
            <p class="text-danger" v-if="errors.installment_payment">{{ errors.installment_payment[0] }}</p>
        </div>
        <div class="form-group">
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
        this.loadEmployees();
        this.loadCompanies();
        this.loadCooperativeMemberType();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('cooperativeMember', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('company', {
            companies: state => state.collectionList,
        }),
        ...mapState('cooperativeMemberType', {
            cooperativeMemberTypes: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('cooperativeMember', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('company', {loadCompanies:'loadList'}),
        ...mapActions('cooperativeMemberType', {loadCooperativeMemberType:'loadList'}),

        changeTotal(){
            var loan = parseInt(this.form.loan_balance);
            var count = parseInt(this.form.installment_count);
            var total = loan/count;

            if (this.form.installment_count != '0'){
                this.form.installment_payment = total;
            }else{
                this.form.installment_payment = loan;
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
        'v-select': vSelect,
        datetime: Datetime,
    }
}
</script>
