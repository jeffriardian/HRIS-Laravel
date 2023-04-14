import Index from "./views/index.vue";

const salaryTypeRoute = [
    {
        path: "salaryTypes",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true }
    }
];

export default salaryTypeRoute;
