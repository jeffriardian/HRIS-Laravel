<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">{{ $t('code') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code" :readonly="this.modelId != null">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.parent_id }">
            <label for="">{{ $tc('parent') }}</label>
            <v-select v-model="form.parent_id" :options="departmentLevels.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.parent_id }"></v-select>
            <p class="text-danger" v-if="errors.parent_id">{{ errors.parent_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.department_level_id }">
            <label for="">{{ $tc('department level') }}</label>
            <v-select v-model="form.department_level_id" :options="departmentLevels.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.department_level_id }"></v-select>
            <p class="text-danger" v-if="errors.department_level_id">{{ errors.department_level_id[0] }}</p>
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
        this.loadDepartmentLevels();
    },
    components: {
        'v-select': vSelect,
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('department', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('departmentLevel', {
            departmentLevels: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('department', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('departmentLevel', {loadDepartmentLevels:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>