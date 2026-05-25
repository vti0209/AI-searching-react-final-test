export function withBase(path) {
    const base = (import.meta.env.VITE_BASE_URL || '').trim();
    // remove trailing slashes from base and leading slashes from path
    const normalizedBase = base.replace(/\/+$/,'')
    const normalizedPath = (path || '').replace(/^\/+/, '')
    return `${normalizedBase}/${normalizedPath}`
}
