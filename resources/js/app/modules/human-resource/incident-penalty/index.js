import Index from './views/index.vue'

export default [
    {
        path: 'incident-penalties',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'incidentPenalties.index',
                component: Index,
                meta: { title: 'Manage Incident Penalty' }
            },
        ]
    }
];
