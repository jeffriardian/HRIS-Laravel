import Index from './views/index.vue'

const trainingRoutes = [
    {
        path: 'trainings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'trainings.index',
                component: Index,
                meta: { title: 'Manage Training' }
            },
        ]
    }
];

export default trainingRoutes;
