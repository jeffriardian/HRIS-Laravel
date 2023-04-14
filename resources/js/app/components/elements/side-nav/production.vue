<template>
    <div>
        <li class="menu-title">Production</li>
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
            </ul>
        </li>
    </div>
</template>
<script>
export default {
    
}
</script>