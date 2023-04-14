import Index from './views/index.vue'

export default [
    {
        path: 'attendances',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'attendances.index',
                component: Index,
                meta: { title: 'Manage Attendance' }
            },
        ]
    }
];
