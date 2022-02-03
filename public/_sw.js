importScripts('/zona/public/js/cache-polifill.js');


self.addEventListener('install', function(e) {
    e.waitUntil(
        caches.open('hulahulazone').then(function(cache) {
            return cache.addAll([
                '/zona/login',
                '/zona/no_conn',
                '/zona/public/css/style.css',
                '/zona/public/css/app.css',
                '/zona/public/vendors/js/vendor.bundle.base.js',
                '/zona/public/vendors/css/vendor.bundle.base.css',
                '/zona/public/vendors/css/vendor.bundle.addons.css',
                '/zona/public/vendors/iconfonts/mdi/css/materialdesignicons.min.css',
            ]);
        })
    );
});


self.addEventListener('fetch', function(event) {

    console.log(event.request.url);

    event.respondWith(

        caches.match(event.request).then(function(response) {

            return response || fetch(event.request);

        }).catch(() => {
                return caches.match('/zona/no_conn');
            })
    );
});
