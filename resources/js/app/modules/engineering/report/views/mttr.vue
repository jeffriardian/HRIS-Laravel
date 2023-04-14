<template>
    <div class="card">
        <div class="card-body">
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
                    </div>
                </div>
            </div>
            <b-table :sort-by.sync="tableParams.order_column" :sort-desc.sync="tableParams.order_direction"
                responsive="sm" thead-class="thead-light" small hover bordered :items="collection.data" :fields="fields" show-empty>
                <template v-slot:head()="data">
                    {{ $tc(data.label) }}
                </template>
                <template v-slot:cell(index)="row">
                    {{ (tableParams.page !== 1) ? (row.index + 1) + (tableParams.per_page) * (tableParams.page - 1) : row.index + 1 }}
                </template>
                <template v-slot:cell(group)="row">
                    {{ row.item.machine_group ? row.item.machine_group.name : '-'}}
                </template>
                <template v-slot:cell(brand)="row">
                    {{ row.item.machine_brand ? row.item.machine_brand.name : '-'}}
                </template>
                <template v-slot:cell(type)="row">
                    {{ row.item.machine_type ? row.item.machine_type.name : '-' }}
                </template>
                <template v-slot:cell(mttr)="row">
                    {{ (row.item.code == 'TSH 13110') ? 90 : '' }} 
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
    </div>
</template>
<script>
import moment from 'moment'
import { mapActions, mapState } from 'vuex'
export default {
    created() {
        this.load(this.tableParams)
    },
    data() {
        return {
            fields: [
                { key: 'index', label: '#', thStyle: 'text-align: center; width: 35px;',tdClass: 'text-center'},
                { key: 'area', label: 'area', sortable: true, thStyle: 'text-align: center; width: 50px;',tdClass: 'text-center' },
                { key: 'group', label: 'group', thStyle: 'width: 180px;'},
                { key: 'brand', label: 'brand', thStyle: 'width: 180px;'},
                { key: 'type', label: 'type', thStyle: 'width: 180px;'},
                { key: 'code', label: 'code', sortable: true, thStyle: 'text-align: center; width: 150px;',tdClass: 'text-center'},
                { key: 'mttr', label: 'mttr', thStyle: 'text-align: center; width: 180px;',tdClass: 'text-center'},
            ],
            keyword:'',
            modelId:null,
            loadingProcess: false
        }
    },
    computed: {
        ...mapState('report', {
            collection: state => state.physicalAvailabilities,
        }),
        tableParams: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE
                return this.$store.state.engineering.report.tableParams
            },
            set(params) {
                this.$store.commit('report/SET_TABLE_PARAMS', params)
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
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE
        ...mapActions('report', {load:'mtbf'}),
        getMtbf(jobCards){
            var duration = 0;
            jobCards.forEach(function(item){
                var stop_at = moment(item.stop_at,'HH:mm'); //todays date
                var start_at = moment(item.start_at,'HH:mm'); // another date
                var ItemDuration = moment.duration(start_at.diff(stop_at))

                duration = duration + ItemDuration.as('hours')
            });
            return (24-duration)/24;
        },
        exportFile(type) {
            window.open(`/api/engineering/reports/mttr-export-${type}?api_token=${this.$store.state.token}`)
        },
    }
}
</script>