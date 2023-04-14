import Index from './views/index.vue'

export default [
    {
        path: 'machine-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machineTypes.index',
                component: Index,
                meta: { title: 'Manage Machine Types' }
            },
        ]
    }
];
