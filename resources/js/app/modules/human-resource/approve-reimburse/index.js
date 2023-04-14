import Index from './views/index.vue'

export default [
    {
        path: 'approve-reimburses',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'approveReimburses.index',
                component: Index,
                meta: { title: 'Manage Approve Reimburse' }
            },
        ]
    }
];
