import Index from './views/index.vue'

const improvementRoutes = [
    {
        path: 'improvement-ideas',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'improvementIdeas.index',
                component: Index,
                meta: { title: 'Manage Improvement Ideas' }
            },
        ]
    }
];

export default improvementRoutes;
