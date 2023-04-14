<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.engineering_weekly_planning_id }">
                <label for="">{{ $t('weekly planning') }}</label>
                <v-select v-model="form.engineering_weekly_planning_id" :options="weeklyPlannings.data" label="week" :reduce="week => week.id" :class="{ 'border-danger': errors.engineering_weekly_planning_id }" :readonly="this.modelId != null"></v-select>
                <p class="text-danger" v-if="errors.engineering_weekly_planning_id">{{ errors.engineering_weekly_planning_id[0] }}</p>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.day }">
                <label for="">{{ $t('day') }}</label>
                <vSelect v-model="form.day" :options="days" :class="{ 'border-danger': errors.day }" :readonly="this.modelId != null"></vSelect>
                <p class="text-danger" v-if="errors.day">{{ errors.day[0] }}</p>
            </div>
        </div>
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h5 class="card-title">Detail</h5>
                    </div>
                    <div class="col-3">
                        <button type="button" @click="addDetail()" class="btn btn-sm btn-rounded btn-success waves-effect waves-light float-right" v-b-tooltip.hover :title="$tc('add')"><i class="mdi mdi-plus"></i></button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-sm table-hover">
                <thead>
                    <tr>
                        <th width="250px">{{ $tc('machine') }}</th>
                        <th width="250px">{{ $tc('service type') }}</th>
                        <th>{{ $t('stop at') }}</th>
                        <th>{{ $t('start at') }}</th>
                        <th width="70px">{{ $t('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in form.details" :key="index" class="justify-content-between align-items-center">
                        <td>
                            <v-select v-model="row.machine_id" :options="machines.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors[`details.${index}.machine_id`] }">
                                <template #option="{code, area, machine_group, machine_type}">
                                    {{ area }} - {{ code }} <br>
                                    <small>{{ machine_group ? machine_group.name : ''}} | {{ machine_type ? machine_type.name : ''}}</small>
                                </template>
                            </v-select>
                            <p class="text-danger" v-if="errors[`details.${index}.machine_id`]">{{ errors[`details.${index}.machine_id`][0] }}</p>
                        </td>
                        <td>
                            <v-select v-model="row.service_type_id" :options="serviceTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors[`details.${index}.service_type_id`] }"></v-select>
                            <p class="text-danger" v-if="errors[`details.${index}.service_type_id`]">{{ errors[`details.${index}.service_type_id`][0] }}</p>
                        </td>
                        <td>
                            <el-time-select :class="{ 'border-danger': errors[`details.${index}.stop_at`] }" v-model="row.stop_at" size="small"
                                :picker-options="{start: '07:00',step: '00:15',end: '17:00' }"/>
                            <p class="text-danger" v-if="errors[`details.${index}.stop_at`]">{{ errors[`details.${index}.stop_at`][0] }}</p>
                        </td>
                        <td>
                            <el-time-select :class="{ 'border-danger': errors[`details.${index}.start_at`] }" v-model="row.start_at" size="small"
                                :picker-options="{start: '07:00',step: '00:15',end: '17:00' }"/>
                            <p class="text-danger" v-if="errors[`details.${index}.start_at`]">{{ errors[`details.${index}.start_at`][0] }}</p>
                        </td>
                        <td class="text-center">
                            <a href='javascript:void(0)' @click="removeDetail(index)" class="text-danger" v-b-tooltip.hover :title="$tc('delete')"><i class="mdi mdi-trash-can-outline font-size-16"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    components: {
        vSelect
    },
    created(){
        this.addDetail()
        this.loadMachines();
        this.loadServiceTypes();
        this.loadWeeklyPlannings();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('dailyPlanning', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('weeklyPlanning', {
            weeklyPlannings: state => state.collectionList,
        }),
        ...mapState('machine', {
            machines: state => state.collectionList,
        }),
        ...mapState('serviceType', {
            serviceTypes: state => state.collectionList,
        }),
        days() {
            const rangeDays = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));
            
            return rangeDays(1, 7, 1);
        }
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('dailyPlanning', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('weeklyPlanning', {loadWeeklyPlannings:'loadList'}),
        ...mapActions('machine', {loadMachines:'loadList'}),
        ...mapActions('serviceType', {loadServiceTypes:'loadList'}),
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addDetail() {
            this.form.details.push({ type: null, description: null});
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removeDetail(index) {
            if (this.form.details.length > 1) {
                this.form.details.splice(index, 1)
            }
        },
        exportTemplate() {
            window.open(`/api/engineering/daily-planning-template?api_token=${this.$store.state.token}`)
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