<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.cooperative_member_id }">
            <label for="">{{ $tc('Employee') }}</label>
            <v-select v-model="form.cooperative_member_id" :options="cooperativeMembers.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.cooperative_member_id }"></v-select>
            <p class="text-danger" v-if="errors.cooperative_member_id">{{ errors.cooperative_member_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.amount }">
            <label for="">{{ $t('Amount') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.amount }" v-model="form.amount">
            <p class="text-danger" v-if="errors.amount">{{ errors.amount[0] }}</p>
        </div>
        <!-- <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div> -->
    </div>
</template>

<script>

import Vue from 'vue'
import { Datetime } from 'vue-datetime'
import moment from 'moment'
import 'vue-datetime/dist/vue-datetime.css'
import 'vue-datetime/dist/vue-datetime.js'
import vSelect from 'vue-select'
import { mapState, mapMutations, mapActions } from 'vuex'

export default {
    props: [
        'modelId'
    ],
    created(){
        this.loadCooperativeMembers();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('saving', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('cooperativeMember', {
            cooperativeMembers: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('saving', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('cooperativeMember', {loadCooperativeMembers:'loadList'}),
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    },
    components: {
        'v-select': vSelect,
        datetime: Datetime,
    }
}
</script>
