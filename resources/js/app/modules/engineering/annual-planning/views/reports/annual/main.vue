<template>
    <div class="card">
        <div class="btn-toolbar p-3" role="toolbar">
            <b-dropdown text="Choose annual planning">
                <b-dropdown-item v-for="(planning, index) in annualPlanningList.data" :key="index"
                    :href="`/engineering/annual-plannings/reports/annual/${planning.id}`">
                    {{ planning.year }}
                </b-dropdown-item>
            </b-dropdown>
        </div>
        <router-view/>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
    created(){
        this.loadList();
    },
    computed: {
        ...mapState('annualPlanning', {
            annualPlanningList: state => state.collectionList,
        }),
    },
    methods: {
        ...mapActions('annualPlanning', ['loadList']),
    },
}
</script>