import Index from './views/index.vue'

export default [
    {
        path: 'positions',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'positions.index',
                component: Index,
                meta: { title: 'Manage Job Level' }
            },
        ]
    }
];
