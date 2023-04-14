<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import vSelect from 'vue-select'
import { mapState, mapActions, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    components: {
        vSelect
    },
    data() {
        return {
            workOrder:[]
        }
    },
    mounted(){
        this.loadWorkOrders();
        this.loadEmployees();
        this.loadMachines();
        this.setWorkOrderSelected(this.form.work_order_id)
        console.log(this.modelId)
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('jobCard', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('workOrder', {
            workOrders: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
        ...mapState('machine', {
            machines: state => state.collectionList,
        }),
        rangeDuration() {
            var stop_at = moment(this.form.stop_at,'HH:mm'); //todays date
            var start_at = moment(this.form.start_at,'HH:mm'); // another date
            var duration = moment.duration(start_at.diff(stop_at))

            return (this.form.stop_at && this.form.start_at) ? duration.as('hours') : '';
        },
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('jobCard', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('workOrder', {loadWorkOrders:'loadList'}),
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions('machine', {loadMachines:'loadList'}),
        setWorkOrderSelected(value) {
            const dataSelected = _.filter(this.workOrders.data, { 'id': value });
            
            this.workOrder = (dataSelected[0]);

            console.log(value)
            console.log(this.workOrder)
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
<style>
.mx-input{
    height: calc(1em + 0.94rem + 2px) !important;
}

.input-duration{
    height: calc(1.2em + 0.94rem + 2px) !important;
}
</style>