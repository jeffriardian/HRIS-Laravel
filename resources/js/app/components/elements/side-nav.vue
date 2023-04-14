<template>
    <div id="sidebar-menu">
        <ul id="side-menu" class="metismenu list-unstyled side-menu">
            <generalMenu />
            <!-- <engineeringMenu/>
            <productionMenu/> -->
            <humanResourceMenu />
        </ul>
    </div>
</template>
<script>
import MetisMenu from "metismenujs/dist/metismenujs";
import engineeringMenu from "./side-nav/engineering";
import humanResourceMenu from "./side-nav/human-resource";
import warehouseMenu from "./side-nav/warehouse";
import generalMenu from "./side-nav/general";
import Navigation from "./side-nav/navigation";
import Menu from "./side-nav/menu";
export default {
    components: {
        generalMenu,
        engineeringMenu,
        humanResourceMenu,
        warehouseMenu,
        Menu,
        Navigation
    },
    mounted: function() {
        document.body.setAttribute("data-sidebar", "dark");
        // eslint-disable-next-line no-unused-vars
        var menuRef = new MetisMenu("#side-menu");
        var links = document.getElementsByClassName("side-nav-link-ref");
        var matchingMenuItem = null;

        for (var i = 0; i < links.length; i++) {
            if (window.location.pathname === links[i].pathname) {
                matchingMenuItem = links[i];
                break;
            }
        }

        if (matchingMenuItem) {
            matchingMenuItem.classList.add("active");
            var parent = matchingMenuItem.parentElement;

            /**
             * TODO: This is hard coded way of expading/activating parent menu dropdown and working till level 3.
             * We should come up with non hard coded approach
             */
            if (parent) {
                parent.classList.add("mm-active");
                const parent2 = parent.parentElement.closest("ul");
                if (parent2 && parent2.id !== "side-menu") {
                    parent2.classList.add("mm-show");

                    const parent3 = parent2.parentElement;
                    if (parent3) {
                        parent3.classList.add("mm-active");
                        var childAnchor = parent3.querySelector(".has-arrow");
                        var childDropdown = parent3.querySelector(
                            ".has-dropdown"
                        );
                        if (childAnchor) childAnchor.classList.add("mm-active");
                        if (childDropdown)
                            childDropdown.classList.add("mm-active");

                        const parent4 = parent3.parentElement;
                        if (parent4) parent4.classList.add("mm-show");
                        const parent5 = parent4.parentElement;
                        if (parent5) parent5.classList.add("mm-active");
                    }
                }
            }
        }
    }
};
</script>
