import Index from './views/index.vue'
  
export default [
    {
        path: 'daily-plannings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'dailyPlannings.index',
                component: Index,
                meta: { title: 'Manage Daily Plannings' }
            },
        ]
    }
];