import Index from './views/index.vue'

const savingRoutes = [
    {
        path: 'savings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'savings.index',
                component: Index,
                meta: { title: 'Manage Savings Cooperative' }
            },
        ]
    }
];

export default savingRoutes;
