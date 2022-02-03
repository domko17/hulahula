importScripts('/zona/public/js/cache-polifill.js');

var version = 'v1::';

self.addEventListener('install', function (e) {
    e.waitUntil(
        caches.open(version + 'hulahulazone').then(function (cache) {
            return cache.addAll([
                '/zona/no_conn',
                '/zona/public/css/style.css',
                '/zona/public/css/app.css',
                '/zona/public/vendors/js/vendor.bundle.base.js',
                '/zona/public/vendors/css/vendor.bundle.base.css',
                '/zona/public/vendors/css/vendor.bundle.addons.css',
                '/zona/public/vendors/iconfonts/mdi/css/materialdesignicons.min.css',
                '/zona/public/manifest.json',
            ]);
        })
    );
});


self.addEventListener('fetch', function (event) {

    //console.log(event.request);

    if (event.request.method !== 'GET') {
        /* If we don't block the event as shown below, then the request will go to
           the network as usual.
        */
        //console.log('WORKER: fetch event ignored.', event.request.method, event.request.url);
        return;
    }

    // request.mode = navigate isn't supported in all browsers
    // so include a check for Accept: text/html header.
    event.respondWith(
        caches
            .match(event.request)
            .then(function (cached) {
                var networked = fetch(event.request)
                // We handle the network request with success and failure scenarios.
                    .then(fetchedFromNetwork, unableToResolve)
                    // We should catch errors on the fetchedFromNetwork handler as well.
                    .catch(unableToResolve);

                //console.log('WORKER: fetch event', cached ? '(cached)' : '(network)', event.request.url);
                return networked;

                function fetchedFromNetwork(response) {
                    return response;
                }

                function unableToResolve() {
                    return caches.match('/zona/no_conn');
                }
            })
    )

});

self.addEventListener('push', function (e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        //notifications aren't supported or permission not granted!
        return;
    }

    //console.log(e);

    if (e.data) {
        var msg = e.data.json();
        //console.log(msg);
        e.waitUntil(self.registration.showNotification(msg.title, {
            body: msg.body,
            icon: msg.icon,
            actions: msg.actions,
            vibrate: msg.vibrate,
        }));
    }
});
