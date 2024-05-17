import { createRouter, createWebHistory } from 'vue-router';

import Home from '../pages/Home.vue'
import Conversion from '../pages/Conversion.vue'
const About = { template: '<div>About</div>' }

const routes = [
    { path: '/', component: Home, name: 'home.page' },
    { path: '/conversion/:uuid', component: Conversion, name: 'conversion.page' },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})