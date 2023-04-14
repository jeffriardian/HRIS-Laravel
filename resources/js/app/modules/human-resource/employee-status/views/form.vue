<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.contract_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.contract_id }">
                <label for="">{{ $tc('Name') }}</label>
                <v-select v-model="form.contract_id" :options="contracts.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.contract_id }"></v-select>
                <p class="text-danger" v-if="errors.contract_id">{{ errors.contract_id[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.period_id }">
            <div class="form-group col-md-12" :class="{ 'has-error': errors.period_id }">
                <label for="">{{ $tc('Period') }}</label>
                <v-select v-model="form.period_id" :options="periods.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.period_id }"></v-select>
                <p class="text-danger" v-if="errors.period_id">{{ errors.period_id[0] }}</p>
            </div>
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
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    created(){
        this.loadContracts();
        this.loadPeriods();
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('employeeStatus', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('contract', {
            contracts: state => state.collectionList,
        }),
        ...mapState('period', {
            periods: state => state.collectionList,
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('employeeStatus', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('contract', {loadContracts:'loadList'}),
        ...mapActions('period', {loadPeriods:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        'v-select': vSelect,
    }
}
</script>
