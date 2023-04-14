import Index from "./views/index.vue";

export default [
    {
        path: "history-employee-positions",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "historyEmployeePositions.index",
                component: Index,
                meta: { title: "Manage Employee Position" }
            }
        ]
    }
];
