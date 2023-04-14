import layout from './layout.vue'

import machine from './machine';
import machineGroup from './machine-group';
import machinePart from './machine-part';
import machineType from './machine-type';
import machineBrand from './machine-brand';

export default [
    {
        path: '/production',
        component: layout,
        children: [
            ...machine,
            ...machineGroup,
            ...machinePart,
            ...machineType,
            ...machineBrand
        ]
    },
];
