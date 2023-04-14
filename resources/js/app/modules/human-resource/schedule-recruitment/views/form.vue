<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.candidate_id }">
            <label for="">{{ $tc('Candidate Name') }}</label>
            <v-select multiple v-model="form.candidate_id" :options="candidates.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.candidate_id }"></v-select>
            <p class="text-danger" v-if="errors.candidate_id">{{ errors.candidate_id[0] }}</p>
        </div>
        
        <div class="form-group" :class="{ 'has-error': errors.recruitment_date }">
            <label for="">{{ $tc('Recruitment Date') }}</label>
            <!-- buat datepicker disini -->
            <datetime type = "date" v-model="form.recruitment_date" format="dd-MM-yyyy" zone="local" value-zone="local">
                <template slot="button-cancel">
                    <fa :icon="['far', 'times']"></fa>
                        Cancel
                    </template>
                    <template slot="button-confirm">
                        <fa :icon="['fas', 'check-circle']"></fa>
                        Confirm
                </template>
            </datetime>
        </div>
        <div class="form-group">
            <b-form-checkbox v-model="form.is_active" value=1 unchecked-value=0>
                {{ $t('status') }}
            </b-form-checkbox>
        </div>
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
        this.loadCandidates();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('scheduleRecruitment', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('candidate', {
            candidates: state => state.collectionList,
        }),
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('scheduleRecruitment', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('candidate', {loadCandidates:'loadList'}),

        
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
