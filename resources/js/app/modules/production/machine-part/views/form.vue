<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.machine_group_id }">
            <label for="">{{ $tc('machine group') }}</label>
            <vSelect v-model="form.machine_group_id" :options="machineGroups.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.machine_group_id }"></vSelect>
            <p class="text-danger" v-if="errors.machine_group_id">{{ errors.machine_group_id[0] }}</p>
        </div>
        <!-- <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">{{ $t('code') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code" :readonly="this.modelId != null">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div> -->
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
import { mapState, mapActions, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    components: {
        vSelect,
    },
    created(){
        this.loadMachineGroups();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('machinePart', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('machineGroup', {
            machineGroups: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('machinePart', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('machineGroup', {loadMachineGroups:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>