import Index from './views/index.vue'

export default [
    {
        path: 'payroll-components',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'payrollComponents.index',
                component: Index,
                meta: { title: 'Manage Payroll Component' }
            },
        ]
    }
];
