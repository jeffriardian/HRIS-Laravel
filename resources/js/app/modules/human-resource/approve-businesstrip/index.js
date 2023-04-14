import Index from './views/index.vue'

export default [
    {
        path: 'approve-businesstrips',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'approveBusinesstrips.index',
                component: Index,
                meta: { title: 'Manage Approve Business Trip' }
            },
        ]
    }
];
