import { createWebHistory, createRouter } from 'vue-router'
import flightserach from './flightserach.vue'


const routes = [

    {
        name: 'flightserach',
        path: '/',
        component: flightserach,

    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
