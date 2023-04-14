import Index from './views/index.vue'

export default [
    {
        path: 'inventory-categories',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryCategories.index',
                component: Index,
                meta: { title: 'Manage Inventory Category' }
            },
        ]
    }
];
