import Index from './views/index.vue'

export default [
    {
        path: 'recruitment-step-parameters',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'recruitmentStepParameters.index',
                component: Index,
                meta: { title: 'Manage Recruitment Step Parameter' }
            },
        ]
    }
];