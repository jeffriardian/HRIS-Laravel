import Index from './views/index.vue'

export default [
    {
        path: 'months',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'months.index',
                component: Index,
                meta: { title: 'Manage Month' }
            },
        ]
    }
];
