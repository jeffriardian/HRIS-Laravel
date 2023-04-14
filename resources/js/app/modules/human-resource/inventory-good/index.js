import Index from './views/index.vue'

export default [
    {
        path: 'inventory-goods',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryGoods.index',
                component: Index,
                meta: { title: 'Manage Inventory Good' }
            },
        ]
    }
];
