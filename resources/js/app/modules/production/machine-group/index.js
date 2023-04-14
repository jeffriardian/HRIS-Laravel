import Index from './views/index.vue'

export default [
    {
        path: 'machine-groups',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machinGroups.index',
                component: Index,
                meta: { title: 'Manage Machine Groups' }
            },
        ]
    }
];
