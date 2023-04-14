import layout from './layout.vue'

import serviceType from './service-type';
import failureType from './failure-type';
import annualPlanning from './annual-planning';
import monthlyPlanning from './monthly-planning';
import weeklyPlanning from './weekly-planning';
import dailyPlanning from './daily-planning';
import workOrderAction from './work-order-action';
import workOrder from './work-order';
import jobCard from './job-card';
import backlog from './backlog';

export default [
    {
        path: '/engineering',
        component: layout,
        children: [
            ...serviceType,
            ...failureType,
            ...annualPlanning,
            ...monthlyPlanning,
            ...weeklyPlanning,
            ...dailyPlanning,
            ...workOrderAction,
            ...workOrder,
            ...jobCard,
            ...backlog
        ]
    },
];
