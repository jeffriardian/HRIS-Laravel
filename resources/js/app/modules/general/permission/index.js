import Main from '../layout.vue'
import Index from './views/index.vue'

export default [
    {
        path: '/permissions',
        component: Main,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'permissions.index',
                component: Index,
                meta: { title: 'Manage Permissions' }
            },
        ]
    }
];