import Index from './views/index.vue'

export default [
    {
        path: 'marital-statuses',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'maritalStatuses.index',
                component: Index,
                meta: { title: 'Manage Marital Status' }
            },
        ]
    }
];
