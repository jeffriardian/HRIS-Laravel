import Vue from 'vue'
import Router from 'vue-router'
import state from '../states'

import generalRoutes from '../modules/general';
import utilitiesRoutes from '../modules/utilities';
import engineeringRoutes from '../modules/engineering';
import humanResourceRoutes from '../modules/human-resource';

Vue.use(Router)

const routes = [
    ...generalRoutes,
    ...utilitiesRoutes,
    ...engineeringRoutes,
    ...humanResourceRoutes
];

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes
});

//Navigation Guards
router.beforeEach((to, from, next) => {
    // Baris code diatas berfungsi untuk membersihkan state errors setiap kali halaman di-load.
    state.commit('CLEAR_ERRORS')
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let auth = state.getters.isAuth
        if (!auth) {
            next({ name: 'login' })
        } else {
            let userId = state.state.utilities.auth.authenticated.id
            if (typeof userId === "undefined") {
                state.dispatch('auth/getUserLogin')
                .then(next);
            }else{
                next()
            }
        }
    } else {
        next()
    }
})

export default router