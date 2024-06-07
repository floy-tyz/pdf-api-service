import { createRouter, createWebHistory } from 'vue-router';

import Convert from '../pages/Convert.vue'
import Combine from '../pages/Combine.vue'
import Conversion from '../pages/Conversion.vue'

const routes = [
    { path: '/conversion/:uuid', component: Conversion, name: 'conversion.page' },
    { path: '/combine', component: Combine, name: 'combine.page' },
    { path: '/convert', component: Convert, name: 'convert.page' },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})