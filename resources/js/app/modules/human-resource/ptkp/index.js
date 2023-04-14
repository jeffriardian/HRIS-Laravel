import Index from './views/index.vue'

export default [
    {
        path: 'ptkps',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'ptkps.index',
                component: Index,
                meta: { title: 'Manage PTKP' }
            },
        ]
    }
];
