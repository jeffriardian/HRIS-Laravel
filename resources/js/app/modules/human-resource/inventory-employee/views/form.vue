<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc('Employee Name') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.inventory_id }">
            <label for="">{{ $tc('Inventory') }}</label>
            <v-select v-model="form.inventory_id" :options="inventories.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.inventory_id }"></v-select>
            <p class="text-danger" v-if="errors.inventory_id">{{ errors.inventory_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.date_borrow }">
            <label for="">{{ $tc('Borrow of Date') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.date_borrow" format="dd-MM-yyyy">
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
        <div class="form-group" :class="{ 'has-error': errors.quantity }">
            <label for="">{{ $t('Quantity') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.quantity }" v-model="form.quantity">
            <p class="text-danger" v-if="errors.quantity">{{ errors.quantity[0] }}</p>
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
        this.loadInventories();
        this.loadEmployees();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('inventoryEmployee', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('inventory', {
            inventories: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('inventoryEmployee', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('inventory', {loadInventories:'loadList'}),
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
