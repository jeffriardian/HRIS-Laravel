import Index from "./views/index.vue";

export default [
    {
        path: "report-recruitments",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "reportRecruitment.index",
                component: Index,
                meta: { title: "Report Recruitment" }
            }
        ]
    }
];
