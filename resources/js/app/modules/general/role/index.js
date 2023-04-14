import Main from '../layout.vue'
import Index from './views/index.vue'

export default [
    {
        path: '/roles',
        component: Main,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'roles.index',
                component: Index,
                meta: { title: 'Manage Roles' }
            },
        ]
    }
];