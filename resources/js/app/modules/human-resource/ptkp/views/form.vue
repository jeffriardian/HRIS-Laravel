<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">{{ $t('PTKP Code') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.amount_ptkp }">
            <label for="">{{ $t('Amount of PTKP') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.amount_ptkp }" v-model="form.amount_ptkp">
            <p class="text-danger" v-if="errors.amount_ptkp">{{ errors.amount_ptkp[0] }}</p>
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('ptkp', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('ptkp', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
