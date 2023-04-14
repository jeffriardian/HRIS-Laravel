import Index from './views/index.vue'
  
export default [
    {
        path: 'improvements',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'improvements.index',
                component: Index,
                meta: { title: 'Manage Improvements' }
            },
        ]
    }
];