import Index from "./views/index.vue";
import Add from "./views/tabs/overtime-spl/add.vue";
import Edit from "./views/tabs/overtime-spl/edit.vue";

export default [
    {
        path: "overtimes",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "overtimes.index",
                component: Index,
                meta: { title: "Manage Overtime" }
            },
            {
                path: "add",
                name: "overtimes.add",
                component: Add,
                meta: { title: "Add New Overtimes" }
            },
            {
                path: "edit/:id",
                name: "overtimes.edit",
                component: Edit,
                meta: { title: "Edit Overtime" }
            }
        ]
    }
];
