<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.engineering_monthly_planning_id }">
                <label for="">{{ $t('monthly planning') }}</label>
                <v-select v-model="form.engineering_monthly_planning_id" :options="monthlyPlannings.data" label="month" :reduce="month => month.id" :class="{ 'border-danger': errors.engineering_monthly_planning_id }" :readonly="this.modelId != null"></v-select>
                <p class="text-danger" v-if="errors.engineering_monthly_planning_id">{{ errors.engineering_monthly_planning_id[0] }}</p>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.week }">
                <label for="">{{ $t('week') }}</label>
                <vSelect v-model="form.week" :options="weeks" :class="{ 'border-danger': errors.week }" :readonly="this.modelId != null"></vSelect>
                <p class="text-danger" v-if="errors.week">{{ errors.week[0] }}</p>
            </div>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.file }" v-if="(this.modelId == null)">
            <label for="">{{ $t('file') }}</label>
            <input type="file" class="form-control" :class="{ 'border-danger': errors.file }" ref="file" v-on:change="handleFileUpload()"/>
            <p class="text-danger" v-if="errors.file">{{ errors.file[0] }}</p>
            <b-link @click="exportTemplate()"><i class="mdi mdi-file-excel"></i> Download Template</b-link>
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
    components: {
        vSelect
    },
    created(){
        this.loadMonthlyPlannings();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('weeklyPlanning', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('monthlyPlanning', {
            monthlyPlannings: state => state.collectionList,
        }),
        weeks() {
            const rangeWeeks = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));
            
            return rangeWeeks(1, 54, 1);
        }
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('weeklyPlanning', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('monthlyPlanning', {loadMonthlyPlannings:'loadList'}),
        handleFileUpload() {
            this.form.file = this.$refs.file.files[0];
        },
        exportTemplate() {
            window.open(`/api/engineering/weekly-planning-template?api_token=${this.$store.state.token}`)
        },
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>