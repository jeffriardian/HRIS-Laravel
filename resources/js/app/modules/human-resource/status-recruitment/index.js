import Index from './views/index.vue'

export default [
    {
        path: 'status-recruitments',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'statusRecruitments.index',
                component: Index,
                meta: { title: 'Manage Status Recruiments' }
            },
        ]
    }
];