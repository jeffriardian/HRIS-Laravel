<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.employee_id }" v-model="form.employee_id">
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.start_time }">
            <label for="">{{ $t('Start Time') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.start_time }" v-model="form.start_time">
            <p class="text-danger" v-if="errors.start_time">{{ errors.start_time[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.end_time }">
            <label for="">{{ $t('End Time') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.end_time }" v-model="form.end_time">
            <p class="text-danger" v-if="errors.end_time">{{ errors.end_time[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
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
        ...mapState('approveOvertime', {
            form: state => state.form //MENGAMBIL STATE DATA
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('approveOvertime', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
