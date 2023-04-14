import Index from './views/index.vue'

const salaryRoutes = [
    {
        path: 'salaries',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'salaries.index',
                component: Index,
                meta: { title: 'Manage Salary' }
            },
        ]
    }
];

export default salaryRoutes;
