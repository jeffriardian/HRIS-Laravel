import Index from './views/index.vue'
  
export default [
    {
        path: 'machines',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machines.index',
                component: Index,
                meta: { title: 'Manage Machines' }
            },
        ]
    }
];