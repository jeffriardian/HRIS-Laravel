<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.engineering_annual_planning_id }">
                <label for="">{{ $t('annual planning') }}</label>
                <v-select v-model="form.engineering_annual_planning_id" :options="annualPlannings.data" label="year" :reduce="year => year.id" :class="{ 'border-danger': errors.engineering_annual_planning_id }" :readonly="this.modelId != null"></v-select>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.month }">
                <label for="">{{ $t(`month`) }}</label>
                <vSelect v-model="form.month" :options="months" :class="{ 'border-danger': errors.month }"></vSelect>
                <p class="text-danger" v-if="errors.month">{{ errors.month[0] }}</p>
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
                        <th width="250px">{{ $tc('machine group') }}</th>
                        <th>250H</th>
                        <th>500H</th>
                        <th>1000H</th>
                        <th>2000H</th>
                        <th width="70px">{{ $t('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in form.details" :key="index" class="justify-content-between align-items-center">
                        <td>
                            <v-select v-model="row.machine_group_id" :options="machineGroups.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors[`details.${index}.machine_group_id`] }"></v-select>
                            <p class="text-danger" v-if="errors[`details.${index}.machine_group_id`]">{{ errors[`details.${index}.machine_group_id`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`details.${index}.ps1`] }" v-model="row.ps1">
                            <p class="text-danger" v-if="errors[`details.${index}.ps1`]">{{ errors[`details.${index}.ps1`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`details.${index}.ps2`] }" v-model="row.ps2">
                            <p class="text-danger" v-if="errors[`details.${index}.ps2`]">{{ errors[`details.${index}.ps2`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`details.${index}.ps3`] }" v-model="row.ps3">
                            <p class="text-danger" v-if="errors[`details.${index}.ps3`]">{{ errors[`details.${index}.ps3`][0] }}</p>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" :class="{ 'border-danger': errors[`details.${index}.ps4`] }" v-model="row.ps4">
                            <p class="text-danger" v-if="errors[`details.${index}.ps4`]">{{ errors[`details.${index}.ps4`][0] }}</p>
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
        this.loadAnnualPlannings();
        this.loadMachineGroups();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('monthlyPlanning', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('annualPlanning', {
            annualPlannings: state => state.collectionList,
        }),
        ...mapState('machineGroup', {
            machineGroups: state => state.collectionList,
        }),
        months() {
            const rangeMonths = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));
            
            return rangeMonths(1, 12, 1);
        }
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('monthlyPlanning', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('annualPlanning', {loadAnnualPlannings:'loadList'}),
        ...mapActions('machineGroup', {loadMachineGroups:'loadList'}),
        //KETIKA TOMBOL TAMBAHKAN DITEKAN, MAKA AKAN MENAMBAHKAN ITEM BARU
        addDetail() {
            this.form.details.push({ machine_group_id: null, ps1: null, ps2: null, ps3: null, ps4: null});
        },
        //KETIKA TOMBOL HAPUS PADA MASING-MASING ITEM DITEKAN, MAKA AKAN MENGHAPUS BERDASARKAN INDEX DATANYA
        removeDetail(index) {
            if (this.form.details.length > 1) {
                this.form.details.splice(index, 1)
            }
        },
        exportTemplate() {
            window.open(`/api/engineering/monthly-planning-template?api_token=${this.$store.state.token}`)
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