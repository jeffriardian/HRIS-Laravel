import PhysicalAvailability from './views/physical-availability.vue'
import Mtbf from './views/mtbf.vue'
import Mttr from './views/mttr.vue'
import Rmc from './views/rmc.vue'

import effectiveHour from './views/effective-hour.vue'
import workOrder from './views/work-order.vue'
import involvement from './views/involvement.vue'

export default [
    {
        path: 'reports',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: 'physical-availability',
                name: 'reports.physicalAvailability',
                component: PhysicalAvailability,
                meta: { title: 'Physical Availability' }
            },
            {
                path: 'mtbf',
                name: 'reports.mtbf',
                component: Mtbf,
                meta: { title: 'Mean Time Between Failure' }
            },
            {
                path: 'mttr',
                name: 'reports.mttr',
                component: Mttr,
                meta: { title: 'Mean Time to Repair' }
            },
            {
                path: 'rmc',
                name: 'reports.rmc',
                component: Rmc,
                meta: { title: 'Repair and Maintenance Cost' }
            },

            {
                path: 'effective-hour',
                name: 'reports.effectiveHour',
                component: effectiveHour,
                meta: { title: 'Effective Hour' }
            },
            {
                path: 'work-order',
                name: 'reports.workOrder',
                component: workOrder,
                meta: { title: 'Work Order' }
            },
            {
                path: 'involvement',
                name: 'reports.involvement',
                component: involvement,
                meta: { title: 'Involvement' }
            },
        ]
    }
];