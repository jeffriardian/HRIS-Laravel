<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.bpjstk }">
            <label for="">{{ $t('BPJS Tenaga Kerja') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.bpjstk }" v-model="form.bpjstk">
            <p class="text-danger" v-if="errors.bpjstk">{{ errors.bpjstk[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.bpjskes }">
            <label for="">{{ $t('BPJS Kesehatan') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.bpjskes }" v-model="form.bpjskes">
            <p class="text-danger" v-if="errors.bpjskes">{{ errors.bpjskes[0] }}</p>
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
        ...mapState('insurance', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('insurance', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
