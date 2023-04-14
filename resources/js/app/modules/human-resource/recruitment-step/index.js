import Index from './views/index.vue'

export default [
    {
        path: 'recruitment-steps',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'recruitmentSteps.index',
                component: Index,
                meta: { title: 'Manage Recruitment Step' }
            },
        ]
    }
];