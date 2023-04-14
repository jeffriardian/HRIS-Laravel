import Index from './views/index.vue'

export default [
    {
        path: 'employee-statuses',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'employeeStatuses.index',
                component: Index,
                meta: { title: 'Manage Employee Status' }
            },
        ]
    }
];
