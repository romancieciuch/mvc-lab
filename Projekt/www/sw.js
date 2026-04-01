const CACHE_NAME = 'flow-app-v2';
const ASSETS_TO_CACHE = [
    '/',
    '/manifest.json',
    '/css/styles.css',
    '/js/scripts.js',
	'/images/flow-white-bcg.svg'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(ASSETS_TO_CACHE))
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                return cachedResponse || fetch(event.request);
            })
    );
});