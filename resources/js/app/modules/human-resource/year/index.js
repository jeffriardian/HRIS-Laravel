import Index from './views/index.vue'

export default [
    {
        path: 'years',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'years.index',
                component: Index,
                meta: { title: 'Manage Year' }
            },
        ]
    }
];
