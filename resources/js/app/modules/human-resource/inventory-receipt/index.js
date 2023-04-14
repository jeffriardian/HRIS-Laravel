import Index from './views/index.vue'

export default [
    {
        path: 'inventory-receipts',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryReceipts.index',
                component: Index,
                meta: { title: 'Manage Inventory Receipt' }
            },
        ]
    }
];
