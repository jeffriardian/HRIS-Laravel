import Index from './views/index.vue'

export default [
    {
        path: 'scores',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'scores.index',
                component: Index,
                meta: { title: 'Manage Score' }
            },
        ]
    }
];