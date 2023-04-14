<template>
    <div class="card">
        <div class="card-body">
            <!-- Card Header -->
            <div class="row mb-2">
                <div class="col-sm-4">
                    <div class="search-box mr-2 mb-2 d-inline-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" :placeholder="$t('search')" v-model="keyword">
                            <i class="bx bx-search-alt search-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="float-right">
                        <b-dropdown>
                            <template v-slot:button-content>
                                {{ $t('export') }}
                                <i class="mdi mdi-chevron-down"></i>
                            </template>
                            <b-dropdown-item @click="exportFile('excel')"><i class="mdi mdi-file-excel"></i> Excel</b-dropdown-item>
                            <b-dropdown-item @click="exportFile('pdf')"><i class="mdi mdi-file-pdf"></i> PDF</b-dropdown-item>
                        </b-dropdown>
                        <b-button v-b-modal="'form-modal'" class="btn btn-success ml-2"><i class="mdi mdi-plus mr-1"></i> {{ $tc('create') }}</b-button>
                    </div>
                </div>
            </div>
            <!-- End Card Header -->
            <b-table :sort-by.sync="tableParams.order_column" :sort-desc.sync="tableParams.order_direction"
                responsive="sm" thead-class="thead-light" small hover bordered :items="collection.data" :fields="fields" show-empty>
                <template v-slot:head()="data">
                    {{ $t(data.label) }}
                </template>
                <template v-slot:cell(index)="row">
                    {{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}
                </template>
                <template v-slot:cell(source)="row">
                    {{ row.item.source == 0 ? $tc('internal') : $tc('external')}}
                </template>
                <template v-slot:cell(type)="row">
                    {{ row.item.type == 0 ? $tc('scheduled') : $tc('unscheduled')}}
                </template>
                <template v-slot:cell(action)="row">
                    {{ row.item.work_order_action ? row.item.work_order_action.name : '-' }}
                </template>
                <template v-slot:cell(is_urgent)="row">
                    <span class="badge badge-pill badge-soft-success font-size-12" v-if="row.item.is_urgent == 1">{{ $t('active') }}</span>
                    <span class="badge badge-pill badge-soft-danger font-size-12" v-else>{{ $t('inactive') }}</span>
                </template>
                <template v-slot:cell(created_at)="row">
                    {{ row.item.created_at | moment("from", "now") }}
                </template>
                <template v-slot:cell(actions)="row">
                    <a href='javascript:void(0)' @click="view(row.item.id)" class="text-primary" v-b-tooltip.hover :title="$tc('update')"><i class="mdi mdi-pencil-box-outline font-size-16"></i></a>
                    <a href='javascript:void(0)' @click="remove(row.item)" class="text-danger" v-b-tooltip.hover :title="$tc('delete')"><i class="mdi mdi-trash-can-outline font-size-16"></i></a>
                </template>
            </b-table>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <div>
                <b-form-select v-model="tableParams.per_page" :options="tableParams.pageOptions"></b-form-select>
            </div>
            <div>
                <p v-if="collection.data">
                    {{ $t('table summary', { from: collection.meta.from, to: collection.meta.to, total: collection.meta.total}) }}
                </p>
            </div>
            <div>
                <b-pagination v-model="tableParams.page" pills
                    :total-rows="collection.meta.total"
                    :per-page="collection.meta.per_page"
                    v-if="collection.data && collection.data.length > 0"
                    ></b-pagination>
            </div>
        </div>
        <b-modal id="form-modal" size="xl" :title="$t('form module',{module:$tc('work order')})" ref="modal">
            <form v-on:submit.prevent="submit">
                <formComponent :modelId=modelId></formComponent>
            </form>
            <template v-slot:modal-footer>
                <b-button class="btn btn-secondary ml-2" @click="hideModal()"><i class="fas fa-arrow-left mr-2"></i> {{ $t('cancel') }}</b-button>
                <button class="btn btn-primary" @click.prevent="submit">
                    <b-spinner v-if="loadingProcess" small class="mr-2"></b-spinner>
                    <i v-else class="far fa-save mr-2"></i> {{ $t('save') }}
                </button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import formComponent from './form.vue'
export default {
    created() {
        this.load(this.tableParams)
    },
    data() {
        return {
            fields: [
                { key: 'index', label: '#', thStyle: 'text-align: center; width: 35px;',tdClass: 'text-center'},
                { key: 'code', label: 'code', sortable: true, thStyle: 'text-align: center; width: 50px;',tdClass: 'text-center'},
                { key: 'source', label: 'source', sortable: true, thStyle: 'text-align: center;',tdClass: 'text-center'},
                { key: 'type', label: 'type', sortable: true, thStyle: 'text-align: center;',tdClass: 'text-center'},
                { key: 'action', label: 'action', sortable: true, thStyle: 'text-align: center;',tdClass: 'text-center'},
                { key: 'created_at', label: 'created at', sortable: true, thStyle: 'text-align: center; width: 150px;', tdClass: 'text-center' },
                { key: 'actions', label: 'action', thStyle: 'text-align: center; width: 80px;',tdClass: 'text-center' }
            ],
            keyword:'',
            modelId:null,
            loadingProcess: false
        }
    },
    components: {
        formComponent,
    },
    computed: {
        ...mapState('workOrder', {
            collection: state => state.collection,
        }),
        ...mapState('notification', {
            notify: state => state.notification,
        }),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.engineering.workOrder.tableParams
            },
            set(params) {
                this.$store.commit('workOrder/SET_TABLE_PARAMS', params)
            }
        },
    },
    watch: {
        tableParams: {
            handler:function(params){
                let requestParams = {
                    keyword:this.keyword,
                    page:params.page,
                    per_page:params.per_page,
                    order_column:params.order_column,
                    order_direction:params.order_direction
                }
                this.load(requestParams);
            },
            deep: true
        },
        keyword(){
            let requestParams = {
                keyword:this.keyword,
                page:1,
                per_page:this.tableParams.per_page,
                order_column:this.tableParams.order_column,
                order_direction:this.tableParams.order_direction
            }
            this.load(requestParams);
        },
        notify() { 
            this.load(this.tableParams)
        },
    },
    mounted() {
        this.$root.$on('bv::modal::hidden', (bvEvent, modalId) => {
            this.modelId = null;
        });
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions('workOrder', ['load','show', 'store', 'update', 'destroy']),
        hideModal(){
            this.loadingProcess = false;
            this.load(this.tableParams);
            this.$bvModal.hide('form-modal')
        },
        view(id){
            this.show(id);
            this.modelId = id;
            this.$bvModal.show('form-modal');
        },
        submit() {
            this.loadingProcess = true;
            if(!this.modelId){
                this.store().then(() => {
                    this.hideModal();
                    this.$message({
                        type: 'success',
                        message: this.$t('present perfect',{object:this.$tc('work order'),message:this.$tc('add',2)})
                    });
                });
            }else{
                this.update(this.modelId).then(() => {
                    this.hideModal();
                    this.$message({
                        type: 'success',
                        message: this.$t('present perfect',{object:this.$tc('work order'),message:this.$tc('update',2)})
                    });
                })
            }
        },
        remove(item) {
            this.$confirm(this.$t('delete confirmation',{item:item.name, module:this.$tc('work order')}), this.$t('warning'), {
                confirmButtonText: this.$t('ok'),
                cancelButtonText: this.$t('cancel'),
                type: 'warning'
            }).then(() => {
                this.destroy(item.id)
                this.$message({
                    type: 'success',
                    message: this.$t('delete successfully')
                });
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: this.$t('delete canceled')
                });
            });
        },
        exportFile(type) {
            window.open(`/api/engineering/work-order-export-${type}?api_token=${this.$store.state.token}`)
        },
    }
}
</script>