import Index from './views/index.vue'

export default [
    {
        path: 'blood-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'bloodTypes.index',
                component: Index,
                meta: { title: 'Manage Blood Types' }
            },
        ]
    }
];