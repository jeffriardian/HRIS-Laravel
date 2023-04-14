<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.employee_id }">
            <label for="">{{ $tc('employee') }}</label>
            <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
            <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">{{ $t('name') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.name }" v-model="form.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password }" v-if="this.modelId == null">
            <label for="">{{ $t('password') }}</label>
            <input type="password" class="form-control" :class="{ 'border-danger': errors.password }" v-model="form.password">
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
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
    created() {
        this.loadEmployees();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('user', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('user', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('employee', {loadEmployees:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        'v-select': vSelect,
    }
}
</script>