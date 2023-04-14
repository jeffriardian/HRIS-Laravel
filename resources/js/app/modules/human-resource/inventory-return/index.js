import Index from './views/index.vue'

export default [
    {
        path: 'inventory-returns',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryReturns.index',
                component: Index,
                meta: { title: 'Manage Return Inventory' }
            },
        ]
    }
];
