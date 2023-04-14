import Index from './views/index.vue'

export default [
    {
        path: 'recruitments',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'recruitments.index',
                component: Index,
                meta: { title: 'Manage Recruitment' }
            },
        ]
    }
];