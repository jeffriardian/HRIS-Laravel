<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.recruitment_step_id }">
            <label for="">{{ $tc('Recruitment Step') }}</label>
            <v-select v-model="form.recruitment_step_id" :options="recruitmentSteps.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.recruitment_step_id }"></v-select>
            <p class="text-danger" v-if="errors.recruitment_step_id">{{ errors.parent_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
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
        this.loadrecruitmentSteps();
    },
    components: {
        'v-select': vSelect,
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('recruitmentStepParameter', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('recruitmentStep', {
            recruitmentSteps: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('recruitmentStepParameter', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('recruitmentStep', {loadrecruitmentSteps:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>