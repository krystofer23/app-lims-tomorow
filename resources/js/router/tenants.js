const requireAuth = (to, from, next) => {
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user") ?? "null");


    if (!token || !user) return next("/guest-gate");

    return next();
};

export const routesTenants = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/tenants/HomeView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/tenants/LoginView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/quotes',
        name: 'quotes',
        component: () => import('../views/tenants/quotes/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/quote-create',
        name: 'quote-create',
        component: () => import('../views/tenants/quotes/CreateView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/quote-update/:id',
        name: 'quote-update',
        component: () => import('../views/tenants/quotes/CreateView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/orders-services',
        name: 'orders-services',
        component: () => import('../views/tenants/orders-service/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/services',
        name: 'services',
        component: () => import('../views/tenants/services/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/companies',
        name: 'companies',
        component: () => import('../views/tenants/companies/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/company-create',
        name: 'company-create',
        component: () => import('../views/tenants/companies/CreateView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/company-update/:id',
        name: 'company-update',
        component: () => import('../views/tenants/companies/UpdateView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/matriz',
        name: 'matriz',
        component: () => import('../views/tenants/matriz/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/essays',
        name: 'essays',
        component: () => import('../views/tenants/essays/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/conditions',
        name: 'conditions',
        component: () => import('../views/tenants/conditions/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/methodologies',
        name: 'methodologies',
        component: () => import('../views/tenants/methodologies/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/units-measurement',
        name: 'units-measurement',
        component: () => import('../views/tenants/units-measurement/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/logistic-cats',
        name: 'logistic-cats',
        component: () => import('../views/tenants/logistic-cats/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
]
