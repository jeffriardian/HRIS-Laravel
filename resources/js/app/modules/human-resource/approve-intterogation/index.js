import Index from "./views/index.vue";

export default [
    {
        path: "approve-intterogations",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "approveIntterogations.index",
                component: Index,
                meta: { title: "Manage Approve Interogation Report" }
            }
        ]
    }
];
