import Index from './views/index.vue'

export default [
    {
        path: 'cooperative-member-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'cooperativeMemberTypes.index',
                component: Index,
                meta: { title: 'Manage Cooperative Member Types' }
            },
        ]
    }
];
