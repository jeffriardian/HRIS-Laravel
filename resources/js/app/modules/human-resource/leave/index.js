import Index from './views/index.vue'

const leaveRoutes = [
    {
        path: 'leaves',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'leaves.index',
                component: Index,
                meta: { title: 'Manage Leave' }
            },
        ]
    }
];

export default leaveRoutes;
