<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.type }">
            <label for="">{{ $t('Type') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.type }" v-model="form.type">
            <p class="text-danger" v-if="errors.type">{{ errors.type[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.serial_number }">
            <label for="">{{ $t('Serial Number') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.serial_number }" v-model="form.serial_number">
            <p class="text-danger" v-if="errors.serial_number">{{ errors.serial_number[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.inventory_category_id }">
            <label for="">{{ $tc('Inventory Category') }}</label>
            <v-select v-model="form.inventory_category_id" :options="inventoryCategories.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.inventory_category_id }"></v-select>
            <p class="text-danger" v-if="errors.inventory_category_id">{{ errors.inventory_category_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.total_stock }">
            <label for="">{{ $t('Quantity') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.total_stock }" v-model="form.total_stock">
            <p class="text-danger" v-if="errors.total_stock">{{ errors.total_stock[0] }}</p>
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
        this.loadinventoryCategories();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('inventory', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('inventoryCategory', {
            inventoryCategories: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('inventory', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('inventoryCategory', {loadinventoryCategories:'loadList'}),
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
