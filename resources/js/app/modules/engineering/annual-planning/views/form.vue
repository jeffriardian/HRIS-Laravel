<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.year }">
            <label for="">{{ $t('year') }}</label>
            <vSelect v-model="form.year" :options="years" :class="{ 'border-danger': errors.year }" :readonly="this.modelId != null"></vSelect>
            <p class="text-danger" v-if="errors.year">{{ errors.year[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.file }" v-if="(this.modelId == null)">
            <label for="">{{ $t('file') }}</label>
            <input type="file" class="form-control" :class="{ 'border-danger': errors.file }" ref="file" v-on:change="handleFileUpload()"/>
            <p class="text-danger" v-if="errors.file">{{ errors.file[0] }}</p>
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
import { mapState, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    components: {
        vSelect
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('annualPlanning', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        years () {
            // https://stackoverflow.com/questions/1575271/range-of-years-in-javascript-for-a-select-box/1575326
            const currentYear = (new Date()).getFullYear();
            const rangeYears = (start, stop, step) => Array.from({ length: (stop - start) / step + 1}, (_, i) => start + (i * step));
            
            return rangeYears(currentYear-1, currentYear +12, 1);
        }
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('annualPlanning', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
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