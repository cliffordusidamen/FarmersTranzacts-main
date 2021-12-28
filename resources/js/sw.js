const STATIC_CACHE_NAME = 'sw-cache-static-v1.4';

const urlsToCache = [
    'molla-template/css/bootstrap.min.css',
    'molla-template/css/plugins/owl-carousel/owl.carousel.css',
    'molla-template/css/plugins/magnific-popup/magnific-popup.css',
    'molla-template/css/plugins/jquery.countdown.css',
    'molla-template/css/style.css',
    'molla-template/css/plugins/nouislider/nouislider.css',
    'molla-template/css/skins/skin-demo-13.css',
    'molla-template/css/demos/demo-13.css',

    '/css/app.css',
    '/js/app.js',

    'plugins/intl-tel-input/css/intlTelInput.min.css',

    'img/icons/icon-72x72.png',
    'img/icons/icon-96x96.png',
    'img/icons/icon-128x128.png',
    'img/icons/icon-144x144.png',
    'img/icons/icon-152x152.png',
    'img/icons/icon-192x192.png',
    'img/icons/icon-384x384.png',
    'img/icons/icon-512x512.png',
    'img/icons/favicon-32x32.png',
    'img/icons/favicon-16x16.png',
    'img/icons/favicon.ico',

    "https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js",
    
    'molla-template/js/jquery.hoverIntent.min.js',
    'molla-template/js/jquery.waypoints.min.js',
    'molla-template/js/superfish.min.js',
    'molla-template/js/owl.carousel.min.js',
    'molla-template/js/bootstrap-input-spinner.js',
    'molla-template/js/jquery.magnific-popup.min.js',
    'molla-template/js/jquery.plugin.min.js',
    'molla-template/js/jquery.countdown.min.js',
    'molla-template/js/jquery.elevateZoom.min.js',
];

// Install service worker
self.addEventListener('install', function(event) {
    console.log('[ServiceWorker] Install');
    event.waitUntil(
        caches.open(STATIC_CACHE_NAME).then(function(cache) {
            
            cache.addAll(urlsToCache);
        })
    );
});


// Activate event
self.addEventListener('activate', function(event) {
    console.log('[ServiceWorker] Activate');
    event.waitUntil(
        caches.keys().then(function(keyList) {
            return Promise.all(
                keyList
                    .filter(key => (key !== STATIC_CACHE_NAME))
                    .map(key => caches.delete(key))
            );
        })
    );

});

// Fetch event
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            if (response) {
                return response
            }

            return fetch(event.request)
                .then(fetchResp => fetchResp)
        })
    );
});