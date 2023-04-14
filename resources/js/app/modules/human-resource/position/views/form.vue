<template>
    <div>
        <div class="form-group col-md-2" :class="{ 'has-error': errors.parent_id }">
            <label for="">{{ $tc('parent') }}</label>
            <v-select v-model="form.parent_id" :options="positions.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.parent_id }"></v-select>
            <p class="text-danger" v-if="errors.parent_id">{{ errors.parent_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">{{ $t('code') }}</label>
            <input type="text" class="form-control" :class="{ 'border-danger': errors.code }" v-model="form.code" :readonly="this.modelId != null">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
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
import { mapState, mapMutations } from 'vuex'
export default {
    props: [
        'modelId'
    ],
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('position', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('position', {
            positions: state => state.collectionList,
        })
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('position', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_ERRORS();
        this.CLEAR_FORM();
    }
}
</script>
