import Index from './views/index.vue'

export default [
    {
        path: 'companies',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'companies.index',
                component: Index,
                meta: { title: 'Manage Company' }
            },
        ]
    }
];
