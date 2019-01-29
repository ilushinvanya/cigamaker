import Vue from 'vue'
import Router from 'vue-router'
import Fabric from './views/Fabric.vue';
import Gallery from './views/Gallery.vue';

Vue.use(Router)

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'Root',
            component: () => import(/* webpackChunkName: "Root" */ './views/Root.vue')
        },
        {
            path: '/register',
            name: 'Register',
            component: () => import(/* webpackChunkName: "Register" */ './views/Register.vue')
        },
        {
            path: '/promo',
            name: 'Promo',
            component: () => import(/* webpackChunkName: "Promo" */ './views/Promo.vue')
        },
        {
            path: '/gifs',
            name: 'Gifs',
            component: () => import(/* webpackChunkName: "Gifs" */ './views/Gifs.vue')
        },
        {
            path: '/create',
            name: 'Create',
            component: Fabric
        },
        {
            path: '/gallery',
            name: 'Gallery',
            component: Gallery,
            children:[{
                path: ':id',
                name: 'Profile',
                component: Gallery
            }]
        },
        {
            path: '/pic/:id',
            name: 'Pic',
            component: () => import(/* webpackChunkName: "about" */ './components/kartina.vue')
        }
    ]
})
