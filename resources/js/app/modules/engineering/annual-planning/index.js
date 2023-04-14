import Index from './views/index.vue'
import View from './views/view.vue'
import AnnualMain from './views/reports/annual/main.vue'
import AnnualTable from './views/reports/annual/table.vue'
  
export default [
    {
        path: 'annual-plannings',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'annualPlannings.index',
                component: Index,
                meta: { title: 'Manage Annual Plannings' }
            },
            {
                path: ':id',
                name: 'annualPlannings.view',
                component: View,
                meta: { title: 'View Annual Planning' }
            },
            {
                path: 'reports/annual',
                name: 'annualPlannings.reportAnnual',
                component: AnnualMain,
                meta: { title: 'Report Annual Planning' },
                children:[
                    { 
                        path: ':id', 
                        component: AnnualTable,
                        meta: { title: 'Report Annual Planning' }
                    }
                ]
            }
        ]
    }
];