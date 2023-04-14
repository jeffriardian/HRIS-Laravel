import Index from './views/index.vue'
  
export default [
    {
        path: 'failure-analysis',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'failureAnalysis.index',
                component: Index,
                meta: { title: 'Manage Failure Analysis' }
            },
        ]
    }
];