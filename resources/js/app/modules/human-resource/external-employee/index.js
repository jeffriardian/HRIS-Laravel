import Index from "./views/index.vue";

export default [
    {
        path: "external-employees",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "externalEmployees.index",
                component: Index,
                meta: { title: "Manage External Employee" }
            }
        ]
    }
];
