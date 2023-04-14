<template>
    <div id="layout-wrapper">
        <NavBar v-if="isAuth"/>
        <SideBar :is-condensed="isMenuCondensed" v-if="isAuth"/>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <slot />
                </div>
            </div>
            <Footer />
        </div>
    </div>
</template>
<script>
import { mapState, mapGetters } from 'vuex'
import NavBar from "../elements/nav-bar";
import SideBar from "../elements/side-bar";
import Footer from "../elements/footer";
export default {
    components: { NavBar, SideBar, Footer },
    data() {
        return {
            isMenuCondensed: false
        };
    },
    computed: {
        ...mapState(['token']),
        ...mapGetters(['isAuth'])
    },
    created: () => {
        document.body.removeAttribute("data-layout", "horizontal");
        document.body.removeAttribute("data-topbar", "dark");
        document.body.removeAttribute("data-layout-size", "boxed");
    },
    methods: {
        toggleMenu() {
            document.body.classList.toggle("sidebar-enable");

            if (window.screen.width >= 992) {
                // eslint-disable-next-line no-unused-vars
                this.$router.afterEach((routeTo, routeFrom) => {
                    document.body.classList.remove("sidebar-enable");
                    document.body.classList.remove("vertical-collpsed");
                });
                document.body.classList.toggle("vertical-collpsed");
            } else {
                // eslint-disable-next-line no-unused-vars
                this.$router.afterEach((routeTo, routeFrom) => {
                    document.body.classList.remove("sidebar-enable");
                });
                document.body.classList.remove("vertical-collpsed");
            }
            this.isMenuCondensed = !this.isMenuCondensed;
        },
    }
}
</script>