import Index from './views/index.vue'

export default [
    {
        path: 'contracts',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'contracts.index',
                component: Index,
                meta: { title: 'Manage Contract Status' }
            },
        ]
    }
];
