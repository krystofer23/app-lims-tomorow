import { createRouter, createWebHistory } from 'vue-router'
import { isCentralHost } from '../tenancy/is-central'
import { routesTenants } from './tenants'
import { routesCentral } from './central'

const routes = isCentralHost() ? routesCentral : routesTenants

const router = createRouter({
    history: createWebHistory(),
    routes: [
        ...routes,
    ]
})

export default router
