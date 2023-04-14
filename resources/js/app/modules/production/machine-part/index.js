import Index from './views/index.vue'

export default [
    {
        path: 'machine-parts',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machineParts.index',
                component: Index,
                meta: { title: 'Manage Machine Parts' }
            },
        ]
    }
];
