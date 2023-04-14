<template>
    <div>
        <li class="menu-title">Engineering<span class="badge badge-pill badge-warning float-right">DEV</span></li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-briefcase-alt-2"></i> <span>Transaction</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/engineering/work-orders" class="side-nav-link-ref">Work Order</router-link></li>
                <li><router-link to="/engineering/job-cards" class="side-nav-link-ref">Job Card</router-link></li>
                <li><router-link to="/engineering/backlogs" class="side-nav-link-ref">Backlog</router-link></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-receipt"></i> <span>Report</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li>
                    <a href="javascript: void(0);" class="has-arrow">Maintenance Planning</a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><router-link to="/engineering/annual-plannings/reports/annual">Annual</router-link></li>
                        <li><a href="javascript: void(0);">Monthly</a></li>
                        <li><a href="javascript: void(0);">Weekly</a></li>
                        <li><a href="javascript: void(0);">Daily</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">KPI</a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="javascript: void(0);">Physical Availability</a></li>
                        <li><a href="javascript: void(0);" title="Repair and Maintenance Cost">RMC</a></li> 
                        <li><a href="javascript: void(0);" title="Mean Time Between Failure">MTBF</a></li> 
                        <li><a href="javascript: void(0);" title="Mean Time to Repair">MTTR</a></li> 
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">Man Power</a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="javascript: void(0);">Effective Hour</a></li>
                        <li><a href="javascript: void(0);">Improvement</a></li>
                        <li><a href="javascript: void(0);">Training & Development</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-copy-alt"></i> <span>Planning</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li>
                    <a href="javascript: void(0);" class="has-arrow">Maintenance</a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><router-link to="/engineering/annual-plannings">Annual</router-link></li>
                        <li><router-link to="/engineering/monthly-plannings">Monthly</router-link></li>
                        <li><router-link to="/engineering/weekly-plannings">Weekly</router-link></li>
                        <li><router-link to="/engineering/daily-plannings">Daily</router-link></li>
                    </ul>
                </li>
                <li><router-link to="/" class="side-nav-link-ref">Part</router-link></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-book-content"></i> <span>Master</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/production/machines" class="side-nav-link-ref">Machine</router-link></li>
                <li><router-link to="/production/machine-groups" class="side-nav-link-ref">Machine Group</router-link></li>
                <li><router-link to="/production/machine-parts" class="side-nav-link-ref">Machine Part</router-link></li>
                <li><router-link to="/production/machine-brands" class="side-nav-link-ref">Machine Brand</router-link></li>
                <li><router-link to="/production/machine-types" class="side-nav-link-ref">Machine Type</router-link></li>
                <li><router-link to="/engineering/service-types" class="side-nav-link-ref">Service Type</router-link></li>
                <li><router-link to="/engineering/failure-types" class="side-nav-link-ref">Failure Type</router-link></li>
                <li><router-link to="/engineering/work-order-actions" class="side-nav-link-ref">Work Order Action</router-link></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-book"></i> <span>Knowledge Base</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/" class="side-nav-link-ref">Machine Library</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Failure Analysis</router-link></li>
                <li><router-link to="/" class="side-nav-link-ref">Map / Layout</router-link></li>
            </ul>
        </li>
    </div>
</template>
<script>
export default {
    
}
</script>