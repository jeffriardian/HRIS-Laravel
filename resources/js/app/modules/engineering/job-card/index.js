import Index from './views/index.vue'
  
export default [
    {
        path: 'job-cards',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'jobCards.index',
                component: Index,
                meta: { title: 'Manage Job Cards' }
            },
        ]
    }
];