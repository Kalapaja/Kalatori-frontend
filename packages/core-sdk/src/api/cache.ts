const cache = new Map();
export function setCache(key: string, value: any, ttl: number = 60000) {
    cache.set(key, { value, expiry: Date.now() + ttl });
}
export function getCache(key: string) {
    const entry = cache.get(key);
    return entry && entry.expiry > Date.now() ? entry.value : null;
}
