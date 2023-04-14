import Index from './views/index.vue'

export default [
    {
        path: 'exit-clearances',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'exitClearances.index',
                component: Index,
                meta: { title: 'Manage Exit Clearances' }
            },
        ]
    }
];
