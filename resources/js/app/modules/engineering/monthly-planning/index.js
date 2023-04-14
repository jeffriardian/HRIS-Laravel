import Index from './views/index.vue'
  
export default [
    {
        path: 'monthly-plannings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'monthlyPlannings.index',
                component: Index,
                meta: { title: 'Manage Monthly Plannings' }
            },
        ]
    }
];