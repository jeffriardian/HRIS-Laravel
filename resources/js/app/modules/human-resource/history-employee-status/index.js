import Index from './views/index.vue'

export default [
    {
        path: 'history-employee-statuses',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'historyEmployeeStatuses.index',
                component: Index,
                meta: { title: 'Manage Employee Status' }
            },
        ]
    }
];
