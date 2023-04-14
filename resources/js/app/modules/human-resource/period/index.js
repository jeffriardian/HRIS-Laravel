import Index from './views/index.vue'

export default [
    {
        path: 'periods',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'periods.index',
                component: Index,
                meta: { title: 'Manage Period COntract' }
            },
        ]
    }
];
