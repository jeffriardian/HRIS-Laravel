import Index from './views/index.vue'
  
export default [
    {
        path: 'failure-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'failureTypes.index',
                component: Index,
                meta: { title: 'Manage Failure Types' }
            },
        ]
    }
];