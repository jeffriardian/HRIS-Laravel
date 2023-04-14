import Index from './views/index.vue'

export default [
    {
        path: 'inventories',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'inventories.index',
                component: Index,
                meta: { title: 'Manage Inventory' }
            },
        ]
    }
];
