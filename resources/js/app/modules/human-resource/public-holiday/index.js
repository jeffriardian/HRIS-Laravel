import Index from './views/index.vue'

export default [
    {
        path: 'public-holidays',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'publicHolidays.index',
                component: Index,
                meta: { title: 'Manage Public Holidays' }
            },
        ]
    }
];
