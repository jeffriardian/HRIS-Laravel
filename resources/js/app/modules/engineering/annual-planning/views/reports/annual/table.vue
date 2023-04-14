<template>
    <div class="body">
        <simpleBar style="max-height: 530px;">
        <b-table-simple bordered hover small>
            <b-thead head-variant="dark">
                <b-tr>
                    <b-th class="text-center align-middle" rowspan="2" scope="col">{{ $tc('group') }}</b-th>
                    <b-th class="text-center align-middle" rowspan="2" scope="col">{{ $tc('machine') }}</b-th>
                    <b-th v-for="(month, index) in yearList" :key="index" class="text-center" colspan="4" scope="col">
                        {{ $tc('monthList.'+month) }}
                    </b-th>
                </b-tr>
                <b-tr>
                    <b-th v-for="(week, index) in weekList" :key="index" class="text-center" scope="col">
                        {{ week.split("_")[1] }}
                    </b-th>
                </b-tr>
            </b-thead>
            <b-tbody class="font-size-12">
                <b-tr v-for="(detail, index) in annuals.details" :key="index">
                    <b-td class="text-center">{{ detail.machine ? detail.machine.machine_group.name : '' }}</b-td>
                    <b-td class="text-center">{{ detail.machine ? detail.machine.code : '' }}</b-td>
                    <b-td v-for="(week, index) in weekList" :key="index" scope="col" :class="{ 'table-warning text-center': detail[week] }">
                        <small>{{ detail ? detail[week] : '' }}</small>
                    </b-td>
                </b-tr>
            </b-tbody>
        </b-table-simple>
        </simpleBar>
    </div>
</template>
<script>
import simpleBar from 'simplebar-vue'
import { mapActions, mapState } from 'vuex'
export default {
    mounted() {
        this.showDetail(this.$route.params.id);
    },
    computed: {
        ...mapState('annualPlanning', {
            annuals: state => state.collectionDetail //MENGAMBIL STATE DATA
        }),
        yearList () {
            var y,data = [];
            for (y = 1; y <= 12; y++) {
                data.push(y);
            }
            
            return data;
        },
        weekList () {
            var y,w, data = [];
            for (y = 1; y <= 12; y++) {
                for (w = 1; w <= 4; w++) {
                    data.push(y+'_'+w);
                }
            }
            
            return data;
        }
    },
    methods: {
        ...mapActions('annualPlanning', ['showDetail']),
    },
    components:{ simpleBar },
}
</script>