import Index from './views/index.vue'

export default [
    {
        path: 'pphs',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'pphs.index',
                component: Index,
                meta: { title: 'Manage PPH 21' }
            },
        ]
    }
];
