import Index from './views/index.vue'

const cooperativeRoutes = [
    {
        path: 'cooperative-members',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'cooperativeMembers.index',
                component: Index,
                meta: { title: 'Manage Cooperative Member' }
            },
        ]
    }
];

export default cooperativeRoutes;
