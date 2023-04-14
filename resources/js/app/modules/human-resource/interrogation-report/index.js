import Index from "./views/index.vue";
import Detail from "./views/detail.vue";

export default [
    {
        path: "interrogation-reports",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "interrogationReports.index",
                component: Index,
                meta: { title: "Manage Interrogation Report" }
            },
            {
                path: "detail/:id",
                name: "interrogationReports.detail",
                component: Detail,
                meta: { title: "Detail Interrogation Report" }
            }
        ]
    }
];
