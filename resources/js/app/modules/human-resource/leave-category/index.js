import Index from './views/index.vue'

export default [
    {
        path: 'leave-categories',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'leaveCategories.index',
                component: Index,
                meta: { title: 'Manage Leave Category' }
            },
        ]
    }
];
