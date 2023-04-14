<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.identity_card_number }">
            <label for="">{{ $t('Identity Card Number') }}</label>
            <input type="number" class="form-control" :class="{ 'border-danger': errors.identity_card_number }" v-model="form.identity_card_number">
            <p class="text-danger" v-if="errors.identity_card_number">{{ errors.identity_card_number[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
            <label for="">{{ $t('Email') }}</label>
            <input type="email" class="form-control" :class="{ 'border-danger': errors.email }" v-model="form.email">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.phone_number }">
            <label for="">{{ $t('Phone Number') }}</label>
            <input type="number" class="form-control" :class="{ 'border-danger': errors.phone_number }" v-model="form.phone_number">
            <p class="text-danger" v-if="errors.phone_number">{{ errors.phone_number[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.position_id }">
            <label for="">{{ $tc('Position') }}</label>
            <v-select v-model="form.position_id" :options="positions.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.position_id }"></v-select>
            <p class="text-danger" v-if="errors.position_id">{{ errors.position_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.company_id }">
            <label for="">{{ $tc('Company') }}</label>
            <v-select v-model="form.company_id" :options="companies.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.company_id }"></v-select>
            <p class="text-danger" v-if="errors.company_id">{{ errors.company_id[0] }}</p>
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
    props: [
        'modelId'
    ],
    created(){
        this.loadPositions();
        this.loadCompanies();
    },
    components: {
        'v-select': vSelect,
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('candidate', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('position', {
            positions: state => state.collectionList,
        }),
        ...mapState('company', {
            companies: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('candidate', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('position', {loadPositions:'loadList'}),
        ...mapActions('company', {loadCompanies:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>