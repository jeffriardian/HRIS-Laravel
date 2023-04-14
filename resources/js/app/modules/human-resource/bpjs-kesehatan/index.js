import Index from "./views/index.vue";

export default [
    {
        path: "bpjs-kesehatan",
        component: {
            render(c) {
                return c("router-view");
            },
            meta: { requiresAUth: true }
        },
        children: [
            {
                path: "",
                name: "bpjs-kesehatan.index",
                component: Index,
                meta: { title: "Manage Bpjs Kesehatan" }
            }
        ]
    }
];
