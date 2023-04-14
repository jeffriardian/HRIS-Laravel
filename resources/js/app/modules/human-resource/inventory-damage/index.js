import Index from './views/index.vue'

export default [
    {
        path: 'inventory-damages',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryDamages.index',
                component: Index,
                meta: { title: 'Manage Inventory Damage' }
            },
        ]
    }
];
