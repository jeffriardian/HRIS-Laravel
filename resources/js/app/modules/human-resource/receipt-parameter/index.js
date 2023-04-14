import Index from './views/index.vue'

export default [
    {
        path: 'receipt-parameters',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'receiptParameters.index',
                component: Index,
                meta: { title: 'Manage Receipt Parameter' }
            },
        ]
    }
];
