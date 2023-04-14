<template>
    <div>
        <b-dropdown menu-class="dropdown-menu-lg p-0"
            toggle-class="header-item noti-icon"
            variant="black"
            right
            >
            <template v-slot:button-content>
                <i class="bx bx-bell"></i>
                <span class="badge badge-danger badge-pill">{{ notificationCount }}</span>
            </template>
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0">Notifications</h6>
                    </div>
                    <div class="col-auto">
                        <a href="#!" class="small">View All</a>
                    </div>
                </div>
            </div>
            <simpleBar style="max-height: 230px;">
                <a v-for="(notification, index) in notifications.data" :key="index" href class="text-reset notification-item">
                    <div class="media">
                        <div class="avatar-xs mr-3">
                            <span class="avatar-title bg-danger rounded-circle font-size-16">
                            <i class="bx bx-trash"></i>
                            </span>
                        </div>
                        <div class="media-body">
                            <h6 class="mt-0 mb-1">{{ $t('warning')}} !!!</h6>
                            <div class="font-size-12 text-muted">
                                <p class="mb-1">
                                    {{ $t('present perfect',{object:notification.data.model_name,message:$tc(notification.data.action,2)}) }}
                                </p>
                                <p class="mb-0">
                                    <i class="mdi mdi-clock-outline"></i> {{ notification.created_at | moment("from", "now") }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
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
import { mapState, mapActions } from 'vuex'
export default {
    components:{ simpleBar },
    
    computed: {
        ...mapState('notification', {
            notifications: state => state.collection,
        }),
        ...mapState('notification', {
            notify: state => state.notification,
        }),
        notificationCount () {
            return this.notifications.data && this.notifications.data.length
        }
    },
    watch: {
        notify: { 
            handler(notify){ 
                this.$notify({
                    title: this.$t('warning'),
                    message: this.$t('present perfect',{object:notify.model_name,message:this.$tc(notify.action,2)}),
                    type: 'warning'
                });
            } 
        },
    },
    created() {
        this.loadNotifications()
        this.connect();
        this.bindChannels();

        //console.log(this.notifications)
    },
    methods: {
        ...mapActions('notification', {loadNotifications:'load'}),
        ...mapActions('notification', ['connect','bindChannels']),
    },
}
</script>