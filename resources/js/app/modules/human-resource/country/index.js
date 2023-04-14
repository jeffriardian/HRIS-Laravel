import Index from './views/index.vue'

export default [
    {
        path: 'countries',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'countries.index',
                component: Index,
                meta: { title: 'Manage Countries' }
            },
        ]
    }
];
