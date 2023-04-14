import Index from './views/index.vue'

export default [
    {
        path: 'department-levels',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'departmentLevels.index',
                component: Index,
                meta: { title: 'Manage Department Levels' }
            },
        ]
    }
];
