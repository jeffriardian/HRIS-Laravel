import Index from './views/index.vue'

const religionRoutes = [
    {
        path: 'religions',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'religions.index',
                component: Index,
                meta: { title: 'Manage Religions' }
            },
        ]
    }
];
  
export default religionRoutes;