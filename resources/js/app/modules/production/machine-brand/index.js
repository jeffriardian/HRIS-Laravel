import Index from './views/index.vue'

export default [
    {
        path: 'machine-brands',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machineBrands.index',
                component: Index,
                meta: { title: 'Manage Machine Brands' }
            },
        ]
    }
];
