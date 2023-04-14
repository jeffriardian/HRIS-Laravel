import Index from './views/index.vue'

const loanRoutes = [
    {
        path: 'loans',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'loans.index',
                component: Index,
                meta: { title: 'Manage Loan' }
            },
        ]
    }
];

export default loanRoutes;
