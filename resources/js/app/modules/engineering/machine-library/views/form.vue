<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.machine_id }">
            <label for="">{{ $tc('machine') }}</label>
            <v-select v-model="form.machine_id" :options="machines.data" label="code" :reduce="code => code.id" :class="{ 'border-danger': errors.machine_id }">
                <template #option="{code, area, machine_group, machine_type}">
                        {{ area }} - {{ code }} <br>
                        <small>{{ machine_group ? machine_group.name : ''}} | {{ machine_type ? machine_type.name : ''}}</small>
                </template>
            </v-select>
            <p class="text-danger" v-if="errors.machine_id">{{ errors.machine_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('description') }}</label>
            <textarea cols="5" rows="5" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.file }" v-if="(this.modelId == null)">
            <label for="">{{ $t('file') }}</label>
            <input type="file" class="form-control" :class="{ 'border-danger': errors.file }" ref="file" v-on:change="handleFileUpload()"/>
            <p class="text-danger" v-if="errors.file">{{ errors.file[0] }}</p>
        </div>
        <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
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
    created(){
        this.loadMachines();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('machineLibrary', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('machine', {
            machines: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('machineLibrary', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('machine', {loadMachines:'loadList'}),
        handleFileUpload() {
            this.form.file = this.$refs.file.files[0];
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