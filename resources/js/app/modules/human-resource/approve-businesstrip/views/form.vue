<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.employee_id }" v-model="form.employee_id">
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.departure_date }">
            <label for="">{{ $t('Departure Date') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.departure_date }" v-model="form.departure_date">
            <p class="text-danger" v-if="errors.departure_date">{{ errors.departure_date[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.back_date }">
            <label for="">{{ $t('Back Date') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.back_date }" v-model="form.back_date">
            <p class="text-danger" v-if="errors.back_date">{{ errors.back_date[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.destination }">
            <label for="">{{ $t('Destination') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.destination }" v-model="form.destination">
            <p class="text-danger" v-if="errors.destination">{{ errors.destination[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.requirement }">
            <label for="">{{ $t('Requirement') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.requirement"></textarea>
            <p class="text-danger" v-if="errors.requirement">{{ errors.requirement[0] }}</p>
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
        ...mapState('approveBusinesstrip', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('approveBusinesstrip', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
