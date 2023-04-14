import Index from './views/index.vue'

export default [
    {
        path: 'office-hours',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'officeHours.index',
                component: Index,
                meta: { title: 'Manage Office Hour' }
            },
        ]
    }
];
