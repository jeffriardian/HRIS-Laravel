import Index from './views/index.vue'

export default [
    {
        path: 'transportations',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'transportations.index',
                component: Index,
                meta: { title: 'Manage Transportation' }
            },
        ]
    }
];
