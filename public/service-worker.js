// filepath: c:\laragon\www\simobi-penetasan-telur-bebek\public\service-worker.js

self.addEventListener('push', function (event) {
    const data = event.data.json();

    const options = {
        body: data.body,
        icon: data.icon || '/default-icon.png', // Replace with your default icon path
        badge: data.badge || '/default-badge.png', // Optional badge icon
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.notification.data && event.notification.data.id) {
        // Handle notification click - e.g., navigate to the specific activity
        clients.openWindow('/owner/penjadwalan/' + event.notification.data.id);
    } else {
        // Default action
        clients.openWindow('/owner/penjadwalan');
    }
});
