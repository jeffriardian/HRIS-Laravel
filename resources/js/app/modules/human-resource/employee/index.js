import Index from './views/index.vue'
import Add from './views/add.vue'
import Edit from './views/edit.vue'

export default [
    {
        path: 'employees',
        component: { render (c) { return c('router-view') }},
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'employees.index',
                component: Index,
                meta: { title: 'Manage Employees' }
            },
            {
                path: 'add',
                name: 'employees.add',
                component: Add,
                meta: { title: 'Add New Employee' }
            },
            {
                path: 'edit/:id',
                name: 'employees.edit',
                component: Edit,
                meta: { title: 'Edit Employee' }
            }
        ]
    }
];