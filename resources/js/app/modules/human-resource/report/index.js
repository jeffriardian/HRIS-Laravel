import interrogation from "./views/interrogation-report/interrogation-report.vue";
import incidentPenalty from "./views/incident-penalty/incident-penalty.vue";
import memorandum from "./views/memorandum/memorandum.vue";

export default [
    {
        path: "reports",
        component: {
            render(c) {
                return c("router-view");
            }
        },
        meta: { requiresAuth: true },
        children: [
            {
                path: "interrogation",
                name: "reports.interrogation",
                component: interrogation,
                meta: { title: "Interrogation" }
            },
            {
                path: "incidentPenalty",
                name: "reports.incidentPenalty",
                component: incidentPenalty,
                meta: { title: "Incident Penalty" }
            },
            {
                path: "memorandum",
                name: "reports.memorandum",
                component: memorandum,
                meta: { title: "Memorandum" }
            }
        ]
    }
];
