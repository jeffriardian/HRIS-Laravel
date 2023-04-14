import Index from './views/index.vue'

export default [
    {
        path: 'inventory-employees',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventoryEmployees.index',
                component: Index,
                meta: { title: 'Manage Request Inventory' }
            },
        ]
    }
];
