import Index from "./views/index.vue";

export default [
    {
        path: "memorandums",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "memorandums.index",
                component: Index,
                meta: { title: "Manage Memorandum" }
            }
        ]
    }
];
