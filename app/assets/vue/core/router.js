import { createRouter, createWebHistory } from 'vue-router';

import Home from '../pages/Home.vue'
const About = { template: '<div>About</div>' }

const routes = [
    { path: '/', component: Home },
    { path: '/about', component: About },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})