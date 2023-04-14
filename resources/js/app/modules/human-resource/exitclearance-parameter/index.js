import Index from './views/index.vue'

export default [
    {
        path: 'exitclearance-parameters',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'exitClearanceParameters.index',
                component: Index,
                meta: { title: 'Manage Exit Clearance Parameter' }
            },
        ]
    }
];
