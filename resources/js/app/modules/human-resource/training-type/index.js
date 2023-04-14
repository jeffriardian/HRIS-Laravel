import Index from './views/index.vue'

export default [
    {
        path: 'training-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'trainingTypes.index',
                component: Index,
                meta: { title: 'Manage Training Types' }
            },
        ]
    }
];
