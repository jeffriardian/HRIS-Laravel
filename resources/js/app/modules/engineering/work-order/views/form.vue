<template>
    <div>
        <b-form-group :class="{ 'has-error': errors.code }"
            label-cols-md="1"
            description="Code is generated autonumber"
            :label="$t('code')"
            label-for="input-code">
            <b-form-input id="input-code" size="sm" :class="{ 'border-danger': errors.code }" v-model="form.code" readonly/>
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </b-form-group>
        <hr>
        <div class="form-row">
            <b-form-group class="col-md-4" :class="{ 'has-error': errors.source }" :label="$t('source')" label-for="source-options">
                <b-form-radio-group
                    id="source-options"
                    @input="setSourceSelected"
                    v-model="form.source"
                    :options="sourceOptions">
                </b-form-radio-group>
                <p class="text-danger" v-if="errors.source">{{ errors.source[0] }}</p>
            </b-form-group>
            <b-form-group class="col-md-4" :class="{ 'has-error': errors.type }" :label="$t('type')" label-for="type-options">
                <b-form-radio-group
                    id="type-options"
                    @input="setTypeSelected"
                    v-model="form.type"
                    :options="typeOptions">
                </b-form-radio-group>
                <p class="text-danger" v-if="errors.type">{{ errors.type[0] }}</p>
            </b-form-group>
            <div class="form-group col-md-4" :class="{ 'has-error': errors.department_id }" v-if="form.source == 1">
                <label for="">{{ $tc('department') }}</label>
                <v-select @input="setDepartmentSelected" v-model="form.department_id" :options="departments.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.department_id }"></v-select>
                <p class="text-danger" v-if="errors.department_id">{{ errors.department_id[0] }}</p>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4" :class="{ 'has-error': errors.work_order_action_id }">
                <label for="">{{ $tc('action') }}</label>
                <v-select @input="setActionSelected" v-model="form.work_order_action_id" :options="workOrderActions.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.work_order_action_id }"></v-select>
                <p class="text-danger" v-if="errors.work_order_action_id">{{ errors.work_order_action_id[0] }}</p>
            </div>
            <div v-if="form.work_order_action_id != 4" class="form-group col-md-4" :class="{ 'has-error': errors.machine_group_id }">
                <label for="">{{ $tc('machine group') }}</label>
                <v-select @input="setGroupSelected" v-model="form.machine_group_id" :options="machineGroups.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.machine_group_id }"></v-select>
                <p class="text-danger" v-if="errors.machine_group_id">{{ errors.machine_group_id[0] }}</p>
            </div>
            <div v-if="form.work_order_action_id != 4" class="form-group col-md-4" :class="{ 'has-error': errors.service_type_id }">
                <label for="">{{ $tc('service type') }}</label>
                <v-select v-model="form.service_type_id" :options="serviceTypes.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.service_type_id }"></v-select>
                <p class="text-danger" v-if="errors.service_type_id">{{ errors.service_type_id[0] }}</p>
            </div>
        </div>

        <div v-if="form.work_order_action_id != 2" class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div v-else>
            <b-table-simple bordered hover small>
                <b-thead head-variant="light">
                    <b-tr>
                        <b-th rowspan="2" class="text-center align-middle" scope="col">#</b-th>
                        <b-th rowspan="2" class="align-middle" scope="col">{{ $tc('part') }}</b-th>
                        <b-th colspan="2" class="text-center align-middle">Cleaning</b-th>
                        <b-th colspan="2" class="text-center align-middle">Tightening</b-th>
                        <b-th colspan="2" class="text-center align-middle">Lubricating</b-th>
                    </b-tr>
                    <b-tr>
                        <b-th class="text-center align-middle" width='135px'>On Position</b-th>
                        <b-th class="text-center align-middle" width='135px'>Remove & Install</b-th>
                        <b-th class="text-center align-middle" width='135px'>Setting & Adjustment</b-th>
                        <b-th class="text-center align-middle" width='135px'>Replace</b-th>
                        <b-th class="text-center align-middle" width='135px'>Oil</b-th>
                        <b-th class="text-center align-middle" width='135px'>Grease</b-th>
                    </b-tr>
                </b-thead>
                <b-tbody>
                    <b-tr v-for="(part, index) in machineParts" :key="index">
                        <b-td class="text-center">{{ index+1 }}</b-td>
                        <b-td>{{ part ? part.name : '' }}</b-td>
                        <b-td class="text-center align-middle"></b-td>
                        <b-td class="text-center align-middle"></b-td>
                        <b-td class="text-center align-middle"></b-td>
                        <b-td class="text-center align-middle"></b-td>
                        <b-td class="text-center align-middle"></b-td>
                        <b-td class="text-center align-middle"></b-td>
                    </b-tr>
                </b-tbody>
            </b-table-simple>
        </div>

        <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_urgent" value=1 unchecked-value=0>
                {{ $t('urgent') }}
            </b-form-checkbox>
        </div> -->
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
    data() {
        return {
            woCode:{
                source:'xxx',
                department:'xxx',
                type:'xxx',
                action:'x',
                group:'xxx',
                id:'{id}'
            },
            sourceOptions: [
                { text: this.$tc('internal'), value: 0},
                { text: this.$tc('external'), value: 1},
            ],
            typeOptions: [
                { text: this.$tc('scheduled'), value: 0},
                { text: this.$tc('unscheduled'), value: 1},
            ],
            machineParts:[]
        }
    },
    created(){
        this.loadWorkOrderActions();
        this.loadMachineGroups();
        this.loadServiceTypes();
        this.loadDepartments();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('workOrder', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('workOrderAction', {
            workOrderActions: state => state.collectionList,
        }),
        ...mapState('machineGroup', {
            machineGroups: state => state.collectionList,
        }),
        ...mapState('serviceType', {
            serviceTypes: state => state.collectionList,
        }),
        ...mapState('department', {
            departments: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('workOrder', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('workOrderAction', {loadWorkOrderActions:'loadList'}),
        ...mapActions('machineGroup', {loadMachineGroups:'loadList'}),
        ...mapActions('serviceType', {loadServiceTypes:'loadList'}),
        ...mapActions('department', {loadDepartments:'loadList'}),
        generateWoCode(){
            var currentDate = (new Date()).toISOString().replace(/-/g, '').split('T')[0];
            if(this.form.source == 1){
                this.form.code = `${this.woCode.source}/${this.woCode.department}/${this.woCode.type}/${this.woCode.action}/${this.woCode.group}/${currentDate}/${this.woCode.id}`;
            }else{
                this.form.code = `${this.woCode.source}/${this.woCode.type}/${this.woCode.action}/${this.woCode.group}/${currentDate}/${this.woCode.id}`;
            }
        },
        setSourceSelected(value) {
            if(value == 0){
                this.form.department_id = null;
            }

            this.woCode.source = (value == 0) ? 'INT' : 'EXT';
            this.generateWoCode();
        },
        setDepartmentSelected(value) {
            const dataSelected = _.filter(this.departments.data, { 'id': value });
            this.woCode.department = dataSelected[0].code;
            
            this.generateWoCode();
        },
        setTypeSelected(value) {
            this.woCode.type = (value == 0) ? 'SCH' : 'USC';
            this.generateWoCode();
        },
        setActionSelected(value) {
            const dataSelected = _.filter(this.workOrderActions.data, { 'id': value });
            this.woCode.action = dataSelected[0].code;
            
            this.generateWoCode();
        },
        setGroupSelected(value) {
            const dataSelected = _.filter(this.machineGroups.data, { 'id': value });
            this.woCode.group = dataSelected[0].code;

            this.machineParts = dataSelected[0].parts;
            
            this.generateWoCode();
        }
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>