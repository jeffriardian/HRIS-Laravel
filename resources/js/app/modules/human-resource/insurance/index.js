import Index from './views/index.vue'

export default [
    {
        path: 'insurances',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'insurances.index',
                component: Index,
                meta: { title: 'Manage Insurance' }
            },
        ]
    }
];
