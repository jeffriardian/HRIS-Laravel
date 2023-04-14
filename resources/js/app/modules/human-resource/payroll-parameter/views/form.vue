<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.type }">
            <label for="">{{ $t('Type') }}</label>
                <b-form-radio-group
                    v-model="form.type"
                    :options="typeOptions"
                ></b-form-radio-group>
            <p class="text-danger" v-if="errors.type">{{ errors.type[0] }}</p>
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
    data() {
        return {
            typeOptions: [
                { text: this.$tc('Allowance'), value: 'Allowance'},
                { text: this.$tc('Deduction'), value: 'Deduction'},
            ],
        }
    },
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('payrollParameter', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('payrollParameter', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
