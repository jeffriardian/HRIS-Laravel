import Index from './views/index.vue'

export default [
    {
        path: 'payrollcomponent-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'payrollComponentTypes.index',
                component: Index,
                meta: { title: 'Manage Payroll Component Type' }
            },
        ]
    }
];
