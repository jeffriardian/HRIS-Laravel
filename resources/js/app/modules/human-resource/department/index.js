import Index from './views/index.vue'

export default [
    {
        path: 'departments',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'departments.index',
                component: Index,
                meta: { title: 'Manage Departments' }
            },
        ]
    }
];