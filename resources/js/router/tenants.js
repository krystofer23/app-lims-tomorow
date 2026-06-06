const requireAuth = (to, from, next) => {
    const token = localStorage.getItem("token");

    let user = null;

    try {
        user = JSON.parse(localStorage.getItem("user") ?? "null");
    } catch (e) {
        localStorage.removeItem("user");
        localStorage.removeItem("token");
        return next("/login");
    }

    if (!token || !user) {
        return next("/login");
    }

    return next();
};

const redirectIfAuthenticated = (to, from, next) => {
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user") ?? "null");

    if (token && user) {
        return next("/");
    }

    return next();
};

export const routesTenants = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/tenants/HomeView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/tenants/LoginView.vue'),
        meta: { scope: 'tenant', requireAuth: false },
        beforeEnter: redirectIfAuthenticated,
    },
    {
        path: '/quotes',
        name: 'quotes',
        component: () => import('../views/tenants/quotes/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/quote-create',
        name: 'quote-create',
        component: () => import('../views/tenants/quotes/CreateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/quote-update/:id',
        name: 'quote-update',
        component: () => import('../views/tenants/quotes/CreateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/services',
        name: 'services',
        component: () => import('../views/tenants/services/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/companies',
        name: 'companies',
        component: () => import('../views/tenants/companies/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/company-create',
        name: 'company-create',
        component: () => import('../views/tenants/companies/CreateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/company-update/:id',
        name: 'company-update',
        component: () => import('../views/tenants/companies/UpdateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/matriz',
        name: 'matriz',
        component: () => import('../views/tenants/matriz/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/essays',
        name: 'essays',
        component: () => import('../views/tenants/essays/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/conditions',
        name: 'conditions',
        component: () => import('../views/tenants/conditions/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/methodologies',
        name: 'methodologies',
        component: () => import('../views/tenants/methodologies/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/units-measurement',
        name: 'units-measurement',
        component: () => import('../views/tenants/units-measurement/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/logistic-cats',
        name: 'logistic-cats',
        component: () => import('../views/tenants/logistic-cats/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/users',
        name: 'users',
        component: () => import('../views/tenants/users/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/orders-services',
        name: 'orders-services',
        component: () => import('../views/tenants/order-services/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/orders-services-create',
        name: 'orders-services-create',
        component: () => import('../views/tenants/order-services/CreateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/orders-services-update/:id',
        name: 'orders-services-update',
        component: () => import('../views/tenants/order-services/CreateView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/reception',
        name: 'reception',
        component: () => import('../views/tenants/reception/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/information',
        name: 'information',
        component: () => import('../views/tenants/information/IndexView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/report-ots',
        name: 'report-ots',
        component: () => import('../views/tenants/reports/ReportOtsView.vue'),
        meta: { scope: 'tenant', requireAuth: true },
        beforeEnter: requireAuth,
    },
    {
        path: '/import-test',
        name: 'import-test',
        component: () => import('../views/tenants/imports/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/laboratory',
        name: 'laboratory',
        component: () => import('../views/tenants/laboratory/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/laboratory-show',
        name: 'laboratory-show',
        component: () => import('../views/tenants/laboratory/CreateView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/operations',
        name: 'operations',
        component: () => import('../views/tenants/operations/IndexView.vue'),
        meta: { scope: 'tenant' }
    },
    {
        path: '/items',
        name: 'items',
        component: () => import('../views/tenants/items/IndexView.vue')
    }
]
