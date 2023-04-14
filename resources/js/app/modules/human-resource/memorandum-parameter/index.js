import Index from './views/index.vue'

export default [
    {
        path: 'memorandum-parameters',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'memorandumParameters.index',
                component: Index,
                meta: { title: 'Manage Memorandum Parameter' }
            },
        ]
    }
];