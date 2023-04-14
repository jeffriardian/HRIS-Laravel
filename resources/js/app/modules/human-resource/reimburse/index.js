import Index from './views/index.vue'

const reimburseRoutes = [
    {
        path: 'reimburses',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'reimburses.index',
                component: Index,
                meta: { title: 'Manage Reimburse' }
            },
        ]
    }
];

export default reimburseRoutes;
