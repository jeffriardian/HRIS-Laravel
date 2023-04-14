import Index from './views/index.vue'

export default [
    {
        path: 'schedule-recruitments',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'scheduleRecruitments.index',
                component: Index,
                meta: { title: 'Manage Schedule Recruiments' }
            },
        ]
    }
];