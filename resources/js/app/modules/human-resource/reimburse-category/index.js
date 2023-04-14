import Index from './views/index.vue'

export default [
    {
        path: 'reimburse-categories',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'reimburseCategories.index',
                component: Index,
                meta: { title: 'Manage Reimburse Category' }
            },
        ]
    }
];
