import Index from './views/index.vue'

export default [
    {
        path: 'payroll-parameters',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'payrollParameters.index',
                component: Index,
                meta: { title: 'Manage Payroll Parameter' }
            },
        ]
    }
];
