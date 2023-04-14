import Index from "./views/index.vue";

const logActivities = [
    {
        path: "activity-logs",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "activityLogs.index",
                component: Index,
                meta: { title: "Activity Logs" }
            }
        ]
    }
];

export default logActivities;
