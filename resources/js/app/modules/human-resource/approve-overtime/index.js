import Index from './views/index.vue'

export default [
    {
        path: 'approve-overtimes',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'approveOvertimes.index',
                component: Index,
                meta: { title: 'Manage Approve Overtime' }
            },
        ]
    }
];
