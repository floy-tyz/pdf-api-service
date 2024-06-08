import { createRouter, createWebHistory } from 'vue-router';

import Home from '../pages/Home.vue'
import OfficeToPdf from '../pages/OfficeToPdf.vue'
import ImageToPdf from '../pages/ImageToPdf.vue'
import Conversion from '../pages/Conversion.vue'

const routes = [
    { path: '/', component: Home, name: 'home.page' },
    { path: '/conversion/:uuid', component: Conversion, name: 'conversion.page' },
    { path: '/image-to-pdf', component: ImageToPdf, name: 'imagetopdf.page' },
    { path: '/office-to-pdf', component: OfficeToPdf, name: 'officetopdf.page' },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})