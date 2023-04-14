<template>
    <div>
        <div class="form-row">
            <div class="form-group col-6" :class="{ 'has-error': errors.machine_type_id }">
                <label for="">{{ $tc('machine type') }}</label>
                <vSelect v-model="form.machine_type_id" :options="machineTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.machine_type_id }"></vSelect>
                <p class="text-danger" v-if="errors.machine_type_id">{{ errors.machine_type_id[0] }}</p>
            </div>
            <div class="form-group col-6" :class="{ 'has-error': errors.machine_brand_id }">
                <label for="">{{ $tc('machine brand') }}</label>
                <vSelect v-model="form.machine_brand_id" :options="machineBrands.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.machine_brand_id }"></vSelect>
                <p class="text-danger" v-if="errors.machine_brand_id">{{ errors.machine_brand_id[0] }}</p>
            </div>
        </div>
        <div class="form-row">
            
            <div class="form-group col-6" :class="{ 'has-error': errors.code }">
                <label for="">{{ $t('code') }}</label>
                <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code" :readonly="this.modelId != null">
                <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
            </div>
            <div class="form-group col-2" :class="{ 'has-error': errors.area }">
                <label for="">{{ $t('area') }}</label>
                <input type="text" class="form-control" :class="{ 'border-danger': errors.area }" v-model="form.area">
                <p class="text-danger" v-if="errors.area">{{ errors.area[0] }}</p>
            </div>
            <div class="form-group col-4" :class="{ 'has-error': errors.year }">
                <label for="">{{ $t('year') }}</label>
                <input type="text" class="form-control" :class="{ 'border-danger': errors.year }" v-model="form.year">
                <p class="text-danger" v-if="errors.year">{{ errors.year[0] }}</p>
            </div>
        </div>
        
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'
import { mapState, mapActions, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    created(){
        this.loadMachineTypes();
        this.loadMachineBrands();
    },
    components: {
        vSelect,
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('machine', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('machineType', {
            machineTypes: state => state.collectionList,
        }),
        ...mapState('machineBrand', {
            machineBrands: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('machine', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('machineType', {loadMachineTypes:'loadList'}),
        ...mapActions('machineBrand', {loadMachineBrands:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>