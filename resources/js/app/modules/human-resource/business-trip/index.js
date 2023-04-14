import Index from "./views/index.vue";
import Add from "./views/add.vue";
import Edit from "./views/edit.vue";
import Detail from "./views/detail.vue";

export default [
    {
        path: "business-trips",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "businessTrips.index",
                component: Index,
                meta: { title: "Manage Business Trip" }
            },
            {
                path: "add",
                name: "businessTrips.add",
                component: Add,
                meta: { title: "Add New Business Trip" }
            },
            {
                path: "edit/:id",
                name: "businessTrips.edit",
                component: Edit,
                meta: { title: "Edit Business Trip" }
            },
            {
                path: "detail/:id",
                name: "businessTrips.detail",
                component: Detail,
                meta: { title: "Detail Business Trip" }
            }
        ]
    }
];
