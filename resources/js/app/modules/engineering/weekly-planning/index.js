import Index from './views/index.vue'
import View from './views/view.vue'
  
export default [
    {
        path: 'weekly-plannings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'weeklyPlannings.index',
                component: Index,
                meta: { title: 'Manage Weekly Plannings' }
            },
            {
                path: ':id',
                name: 'weeklyPlannings.view',
                component: View,
                meta: { title: 'View Weekly Planning' }
            },
        ]
    }
];