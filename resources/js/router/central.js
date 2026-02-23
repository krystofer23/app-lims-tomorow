export const routesCentral = [
    { path: '/', redirect: '/tenants' },
    {
        path: '/',
        name: 'central.tenants',
        component: () => import('../views/central/HomeView.vue'),
        meta: { scope: 'central' }
    },
]
