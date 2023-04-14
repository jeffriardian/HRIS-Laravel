import Index from './views/index.vue'

export default [
    {
        path: 'payroll-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'payrollTypes.index',
                component: Index,
                meta: { title: 'Manage Payroll Types' }
            },
        ]
    }
];
