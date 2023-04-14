import Main from '../layout.vue'
import Index from './views/index.vue'
import Assignment from './views/assignment.vue'

export default [
    {
        path: '/users',
        component: Main,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'users.index',
                component: Index,
                meta: { title: 'Manage Users' }
            },
            {
                path: 'assignment',
                name: 'users.assignment',
                component: Assignment,
                meta: { title: 'User Assignment' }
            },
        ]
    }
];
