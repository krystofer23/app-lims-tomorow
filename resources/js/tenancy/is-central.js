export function isCentralHost() {
    const host = window.location.hostname.toLowerCase()
    const local_host = `${import.meta.env.VITE_BASE_DOMAIN}`

    if (host === 'localhost' || host === '127.0.0.1' || host === local_host) return true

    return host.startsWith('central.')
}

export function getTenantSubdomain() {
    const host = window.location.hostname.toLowerCase()
    const parts = host.split('.')

    return parts.length >= 3 ? parts[0] : null
}
