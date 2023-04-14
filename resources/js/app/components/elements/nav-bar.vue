<template>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <router-link tag="a" to="/" class="logo logo-dark">
                        <span class="logo-sm"><img src="/assets/images/logo.svg" alt height="22" /></span>
                        <span class="logo-lg"><img src="/assets/images/logo-dark.png" alt height="17" /></span>
                    </router-link>
                    <router-link tag="a" to="/" class="logo logo-light">
                        <span class="logo-sm"><img src="/assets/images/logo-light.svg" alt height="22" /></span>
                        <span class="logo-lg"><img src="/assets/images/logo-light.png" alt height="19" /></span>
                    </router-link>
                </div>
                <button @click="toggleMenu" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn" type="button">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <subModuleBar/>
                <languageBar/>
            </div>
            <div class="d-flex">
                <form class="app-search d-none d-lg-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." />
                        <span class="bx bx-search-alt"></span>
                    </div>
                </form>
                <b-dropdown class="d-inline-block d-lg-none ml-2" toggle-class="header-item noti-icon"
                    menu-class="dropdown-menu-lg p-0"
                    variant="black" right>
                    <template v-slot:button-content><i class="mdi mdi-magnify"></i></template>
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Search ..."
                                    aria-label="Recipient's username" />
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </b-dropdown>
                <moduleBar/>
                <fullScreen/>
                <reminder/>
                <notification/>
                <profile/>
            </div>
        </div>
    </header>
</template>
<script>
import fullScreen from './nav-bar/full-screen'
import languageBar from './nav-bar/language'
import subModuleBar from './nav-bar/sub-module'
import moduleBar from './nav-bar/module'
import notification from './nav-bar/notification'
import reminder from './nav-bar/reminder'
import profile from './nav-bar/profile'
import { mapState } from 'vuex'
export default {
    components: {fullScreen, languageBar, moduleBar, subModuleBar, notification, reminder, profile },
    methods: {
        toggleMenu() {
            this.$parent.toggleMenu();
        }
    }
}
</script>