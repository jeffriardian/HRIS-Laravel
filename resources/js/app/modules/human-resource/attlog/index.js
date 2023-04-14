import Index from './views/index.vue'

export default [
    {
        path: 'attlogs',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'attlogs.index',
                component: Index,
                meta: { title: 'Manage Finger Print Machine Attendance' }
            },
        ]
    }
];
