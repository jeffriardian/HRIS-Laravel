/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import _ from "lodash";

import App from "./app/index.vue";
import router from "./app/router";
import store from "./app/states";

import permissions from "./app/mixins/permission.js";
import calculateTotalDuration from "./app/mixins/calculateTotalDuration.js";

import vClickOutside from "./app/plugins/v-click-outside";
import bootstrapVue from "./app/plugins/bootstrap-vue";
import vDatePicker from "./app/plugins/vue-datepicker";
import elementUi from "./app/plugins/element-ui";
import pragment from "./app/plugins/vue-pragment";
import vMoment from "./app/plugins/vue-moment";
import i18n from "./app/plugins/vue-i18n";

import { mapActions, mapGetters } from "vuex";
Vue.mixin(permissions);
Vue.mixin(calculateTotalDuration);

new Vue({
    router,
    store,
    vClickOutside,
    bootstrapVue,
    vDatePicker,
    elementUi,
    pragment,
    vMoment,
    i18n,
    computed: {
        ...mapGetters(["isAuth"])
    },
    methods: {
        ...mapActions("auth", ["getUserLogin"])
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin();
        }
    },
    render: h => h(App)
}).$mount("#app");
