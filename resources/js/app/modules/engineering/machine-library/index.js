import Index from './views/index.vue'
  
export default [
    {
        path: 'machine-libraries',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'machineLibraries.index',
                component: Index,
                meta: { title: 'Manage Machine Libraries' }
            },
        ]
    }
];