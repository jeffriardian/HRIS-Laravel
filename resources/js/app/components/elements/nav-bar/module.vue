<template>
    <b-dropdown class="d-none d-lg-inline-block noti-icon"
        menu-class="dropdown-menu-lg"
        toggle-class="header-item"
        variant="black"
        right
        >
        <template v-slot:button-content>
            <i class="bx bx-customize"></i>
        </template>
        <div class="px-lg-2">
            <div class="row no-gutters">
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/delivery.png" alt="Warehouse" />
                    <span>Delivery</span>
                    </a>
                </div>
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/warehouse.png" alt="Warehouse" />
                    <span>Warehouse</span>
                    </a>
                </div>
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/engineering.png" alt="Warehouse" />
                    <span>Engineering</span>
                    </a>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/planning.png" alt="Warehouse" />
                    <span>Planning</span>
                    </a>
                </div>
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/production.png" alt="Warehouse" />
                    <span>Production</span>
                    </a>
                </div>
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/finance.png" alt="Warehouse" />
                    <span>Finance</span>
                    </a>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/human-resources.png" alt="Warehouse" />
                    <span>Human Resources</span>
                    </a>
                </div>
                <div class="col">
                    <a class="dropdown-icon-item" href="javascript: void(0);">
                    <img src="/assets/images/modules/laboratorium.png" alt="Warehouse" />
                    <span>Laboratorium</span>
                    </a>
                </div>
            </div>
        </div>
    </b-dropdown>
</template>
<script>
export default {

}
</script>