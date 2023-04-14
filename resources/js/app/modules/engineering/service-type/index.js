import Index from './views/index.vue'
  
export default [
    {
        path: 'service-types',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'serviceTypes.index',
                component: Index,
                meta: { title: 'Manage Service Types' }
            },
        ]
    }
];