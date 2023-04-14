import Index from './views/index.vue'
  
export default [
    {
        path: 'type-services',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'typeServices.index',
                component: Index,
                meta: { title: 'Manage Type Services' }
            },
        ]
    }
];