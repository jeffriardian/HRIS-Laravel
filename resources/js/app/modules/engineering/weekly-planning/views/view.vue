<template>
    <div class="card">
        <simpleBar style="max-height: 530px;">
        <b-table-simple bordered hover small>
            <b-thead head-variant="dark">
                <b-tr>
                    <b-th class="text-center align-middle" scope="col">{{ $tc('code') }}</b-th>
                    <b-th class="align-middle" scope="col">{{ $tc('group') }}</b-th>
                    <b-th v-for="(day, index) in dayList" :key="index" class="text-center" scope="col">
                        {{ day }}
                    </b-th>
                </b-tr>
            </b-thead>
            <b-tbody class="font-size-12">
                <b-tr v-for="(detail, index) in weeks.details" :key="index">
                    <b-td class="text-center">{{ detail.machine_group ? detail.machine_group.code : '' }}</b-td>
                    <b-td>{{ detail.machine_group ? detail.machine_group.name : '' }}</b-td>
                    <b-td v-for="(day, index) in dayList" :key="index" class="text-center" scope="col">
                        {{ detail[day-1] }}
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
        ...mapState('weeklyPlanning', {
            weeks: state => state.collectionDetail //MENGAMBIL STATE DATA
        }),
        dayList () {
            var d,data = [];
            for (d = 1; d <= 7; d++) {
                data.push(d);
            }
            
            return data;
        },
    },
    methods: {
        ...mapActions('weeklyPlanning', ['showDetail']),
    },
    components:{ simpleBar },
}
</script>