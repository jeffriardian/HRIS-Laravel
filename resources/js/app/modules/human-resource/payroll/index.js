import Index from './views/index.vue'

export default [
    {
        path: 'payrolls',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'payrolls.index',
                component: Index,
                meta: { title: 'Manage Payroll' }
            },
        ]
    }
];
