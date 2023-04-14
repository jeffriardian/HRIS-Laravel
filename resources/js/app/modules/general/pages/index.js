import Dashboard from './views/dashboard.vue'
import About from './views/about.vue'
import NotFound from './views/not-found.vue'

export default [
    {
        path: '*',
        name: 'not-found',
        component: NotFound,
        meta: { 
            title: 'Not Found',
        }
    },
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { 
            title: 'Dashboard', 
            requiresAuth: true,
        }
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        meta: { requiresAuth: true }
    },
];