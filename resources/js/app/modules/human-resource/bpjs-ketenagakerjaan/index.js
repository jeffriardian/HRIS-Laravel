import Index from "./views/index.vue";

export default [
    {
        path: "bpjs-ketenagakerjaan",
        component: {
            render(c) {
                return c("router-view");
            },
            meta: { requiresAUth: true }
        },
        children: [
            {
                path: "",
                name: "bpjs-ketenagakerjaan.index",
                component: Index,
                meta: { title: "Manage Bpjs Ketenagakerjaan" }
            }
        ]
    }
];
