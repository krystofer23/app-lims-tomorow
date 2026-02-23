import { isCentralHost } from '@/tenancy/isCentral'

export function setupGuards(router) {
    router.beforeEach((to) => {
        const central = isCentralHost()

        if (central && to.meta.scope === 'tenant') {
            return { name: 'central.tenants' }
        }

        if (!central && to.meta.scope === 'central') {
            return { name: 'tenant.dashboard' }
        }

        return true
    })
}
