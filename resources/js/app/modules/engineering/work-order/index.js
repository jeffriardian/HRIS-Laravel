import Index from './views/index.vue'
  
export default [
    {
        path: 'work-orders',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'workOrders.index',
                component: Index,
                meta: { title: 'Manage Work Orders' }
            },
        ]
    }
];