<template>
    <div>
        <li class="menu-title">General</li>
        <li>
            <router-link to="/" class="side-nav-link-ref">
                <i class="bx bx-home-circle"></i> <span>Dashboard</span>
            </router-link>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow">
            <i class="bx bx-book-content"></i> <span>Authentication</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><router-link to="/users" class="side-nav-link-ref">User</router-link></li>
                <li><router-link to="/roles" class="side-nav-link-ref">Role</router-link></li>
                <li><router-link to="/permissions" class="side-nav-link-ref">Permission</router-link></li>
            </ul>
        </li>
    </div>
</template>
<script>
export default {
    
}
</script>