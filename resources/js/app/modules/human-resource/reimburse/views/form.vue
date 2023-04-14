<template>
    <div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.reimburse_category_id }">
                <label for="">{{ $tc('Reimburse Category') }}</label>
                <v-select v-model="form.reimburse_category_id" :options="reimburseCategories.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.reimburse_category_id }"></v-select>
                <p class="text-danger" v-if="errors.reimburse_category_id">{{ errors.reimburse_category_id[0] }}</p>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.employee_id }">
                <label for="">{{ $tc('Employee Name') }}</label>
                
                <v-select v-model="form.employee_id" :options="employees.data" label="name" :reduce="name => name.id" :class="{ 'border-danger': errors.employee_id }"></v-select>
                <p class="text-danger" v-if="errors.employee_id">{{ errors.employee_id[0] }}</p>
            </div>
        </div>
        
        <div class="form-group" :class="{ 'has-error': errors.description }">
            <label for="">{{ $t('Description') }}</label>
            <textarea cols="5" rows="4" class="form-control" v-model="form.description"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0] }}</p>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6" :class="{ 'has-error': errors.total_cost }">
                <label for="">{{ $t('Total Cost') }}</label>
                <input type="text" class="form-control" :class="{ 'border-danger': errors.total_cost }" v-model="form.total_cost">
                <p class="text-danger" v-if="errors.total_cost">{{ errors.total_cost[0] }}</p>
            </div>
            <div class="form-group col-md-6" :class="{ 'has-error': errors.reimburse_type }">
                <label for="">{{ $t('Reimburse Type') }}</label>
                <b-form-radio-group
                    v-model="form.reimburse_type"
                    :options="reimburseTypeOptions"
                ></b-form-radio-group>
            </div>
        </div>
        <label for="">{{ $t('File') }}</label>
        <div class="card">
            <el-upload
                action=""
                list-type="picture-card"
                :on-preview="handlePictureCardPreview"
                :on-change="updateImageList"
                :file-list="modelId ? fileList : null"
                :auto-upload="false">
                <i class="el-icon-plus"></i>
            </el-upload>
            <el-dialog :visible.sync="dialogVisible">
                <img width="100%" :src="dialogImageUrl" alt />
            </el-dialog>
        </div>
        <!-- {{ form.reimburse_type }} -->
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
    data() {
        return {
            reimburseTypeOptions: [
                { text: this.$tc('Payroll'), value: 'payroll'},
                { text: this.$tc('Cash'), value: 'cash'},
            ],
            dialogImageUrl: "",
            dialogVisible: false,
        }
    },
    props: [
        'modelId'
    ],
    created(){
        this.loadreimburseCategories();
        this.loadEmployees();
    },
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('reimburse', {
            form: state => state.form //MENGAMBIL STATE DATA
        }),
        ...mapState('reimburseCategory', {
            reimburseCategories: state => state.collectionList,
        }),
        ...mapState('employee', {
            employees: state => state.collectionList,
        }),
         fileList(){
            console.log(this.form.images);
            return this.form.images != null ? this.form.images.map(function(image) {
                return {
                    name: image.caption,
                    url: `/storage/human-resources/reimburse/${image.path}`
                }
            }) : [];
        }
    },
    methods: {
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapMutations('reimburse', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('reimburseCategory', {loadreimburseCategories:'loadList'}),
        ...mapActions('employee', {loadEmployees:'loadList'}),
        ...mapActions("reimburse", ["store"]), //PANGGIL ACTIONS
        updateImageList(file) {
            this.form.images.push(file.raw);
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.form.images.push(file);
            this.dialogVisible = true;
        },
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
