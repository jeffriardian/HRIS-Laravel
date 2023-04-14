import Index from './views/index.vue'

export default [
    {
        path: 'disbursements',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'disbursements.index',
                component: Index,
                meta: { title: 'Manage Disbursement' }
            },
        ]
    }
];
