import Index from './views/index.vue'
  
export default [
    {
        path: 'backlogs',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'backlogs.index',
                component: Index,
                meta: { title: 'Manage Backlogs' }
            },
        ]
    }
];