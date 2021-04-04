import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Profile from './components/Profile'
import Dashboard from './components/Dashboard'

let routes = [
    { path: '/profile', component: Profile },
    { path: '/dashboard', component: Dashboard }
]

const router = new VueRouter({
    routes
})

export default new Router({
    mode: 'history',
    router
})
