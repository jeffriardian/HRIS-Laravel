import Index from "./views/index.vue";

export default [
    {
        path: "cities",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "cities.index",
                component: Index,
                meta: { title: "Manage City" }
            }
        ]
    }
];
