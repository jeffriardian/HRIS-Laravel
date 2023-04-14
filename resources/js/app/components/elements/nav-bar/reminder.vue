<template>
    <div>
        <b-dropdown menu-class="dropdown-menu-lg p-0"
            toggle-class="header-item noti-icon"
            variant="black"
            right
            >
            <template v-slot:button-content>
                <i class="bx bx-time-five"></i>
                <span class="badge badge-warning badge-pill">0</span>
            </template>
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0">Reminders</h6>
                    </div>
                    <div class="col-auto">
                        <a href="#!" class="small">View All</a>
                    </div>
                </div>
            </div>
            <simpleBar style="max-height: 230px;">
                
            </simpleBar>
            <div class="p-2 border-top">
                <a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">
                <i class="mdi mdi-arrow-down-circle mr-1"></i> Load More..
                </a>
            </div>
        </b-dropdown>
    </div>
</template>
<script>
import simpleBar from 'simplebar-vue'
export default {
    components:{ simpleBar },
}
</script>