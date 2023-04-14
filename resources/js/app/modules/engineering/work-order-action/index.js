import Index from './views/index.vue'
  
export default [
    {
        path: 'work-order-actions',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'workOrderActions.index',
                component: Index,
                meta: { title: 'Manage Work Order Actions' }
            },
        ]
    }
];