import Index from './views/index.vue'

const loanTransactions = [
    {
        path: 'loan-transactions',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'loanTransactions.index',
                component: Index,
                meta: { title: 'Manage Loan Cooperative' }
            },
        ]
    }
];

export default loanTransactions;
