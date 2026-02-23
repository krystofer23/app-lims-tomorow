export const routesTenants = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/tenants/HomeView.vue'),
        meta: { scope: 'tenant' }
    },
]
