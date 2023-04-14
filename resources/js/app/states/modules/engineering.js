import serviceType from '../../modules/engineering/service-type/state'
import failureType from '../../modules/engineering/failure-type/state'
import annualPlanning from '../../modules/engineering/annual-planning/state'
import monthlyPlanning from '../../modules/engineering/monthly-planning/state'
import weeklyPlanning from '../../modules/engineering/weekly-planning/state'
import dailyPlanning from '../../modules/engineering/daily-planning/state'
import workOrderAction from '../../modules/engineering/work-order-action/state'
import workOrder from '../../modules/engineering/work-order/state'
import jobCard from '../../modules/engineering/job-card/state'
import backlog from '../../modules/engineering/backlog/state'
import machine from '../../modules/engineering/machine/state'

export default {
    modules:{
        machine,
        serviceType,
        failureType,
        annualPlanning,
        monthlyPlanning,
        weeklyPlanning,
        dailyPlanning,
        workOrderAction,
        workOrder,
        jobCard,
        backlog
    }
};