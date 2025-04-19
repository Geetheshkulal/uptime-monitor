const filesToCache = [
    '/',
    '/manifest.json',
    'mainlogo.png',
    'offline.html'
];

const preLoad = function() {
    return caches.open("offline").then(function(cache) {
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function(event) {
    event.waitUntil(preLoad());
});

const checkResponse = function(request) {
    return new Promise(function(fulfill, reject) {
        fetch(request).then(function(response) {
            // Only reject if the request fails completely (network error)
            // Not for 404s - let those go through to the server
            fulfill(response);
        }).catch(reject);
    });
};

const addToCache = function(request) {
    return caches.open("offline").then(function(cache) {
        return fetch(request).then(function(response) {
            // Only cache successful responses
            if (response && response.status === 200) {
                return cache.put(request, response);
            }
            return response;
        });
    });
};

const returnFromCache = function(request) {
    return caches.open("offline").then(function(cache) {
        return cache.match(request).then(function(matching) {
            if (!matching) {
                // Only return offline.html for navigation requests when offline
                if (request.mode === 'navigate') {
                    return cache.match('/offline.html');
                }
                return Promise.reject('no-match');
            }
            return matching;
        });
    });
};

self.addEventListener("fetch", function(event) {
    // Let non-GET requests and non-http requests pass through
    if (event.request.method !== 'GET' || !event.request.url.startsWith('http')) {
        event.respondWith(fetch(event.request));
        return;
    }

    // For HTML requests, try network first, then cache, then offline page
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => returnFromCache(event.request))
        );
    } 
    // For other requests, try cache first, then network
    else {
        event.respondWith(
            caches.match(event.request).then(function(response) {
                return response || fetch(event.request);
            })
        );
    }
    
    // Update cache in the background for successful requests
    event.waitUntil(
        fetch(event.request).then(function(response) {
            if (response && response.status === 200) {
                return caches.open("offline").then(function(cache) {
                    return cache.put(event.request, response);
                });
            }
        }).catch(() => {})
    );
});


self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();
        const title = data.title || "New Notification";
        const options = {
            body: data.body || "You've got a new message!",
            icon: data.icon || '/logo.png',
            data: {
                url: data.url || '/' // This will now contain '/dashboard/{token}'
            }
        };
        event.waitUntil(self.registration.showNotification(title, options));
    }
});

self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked');
    
    // Close the notification
    event.notification.close();
    
    // Get the URL from notification data
    const urlToOpen = event.notification.data.url || '/';
    
    // Focus the app if it's already open, otherwise open it
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function(windowClients) {
            // Check if there's already a window/tab open with the app
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url.includes(urlToOpen) && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // If no app window is open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

