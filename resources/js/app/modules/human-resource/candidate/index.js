import Index from './views/index.vue'

export default [
    {
        path: 'candidates',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'candidates.index',
                component: Index,
                meta: { title: 'Manage Candidates' }
            },
        ]
    }
];