import Index from "./views/index.vue";

const kpiRoutes = [
    {
        path: "kpi",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "kpi.index",
                component: Index,
                meta: { title: "Manage Kpi" }
            }
        ]
    }
];

export default kpiRoutes;
