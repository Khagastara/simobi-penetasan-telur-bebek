self.addEventListener('push', function (e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const data = e.data?.json() ?? {};
    const title = data.title || 'New Notification';
    const options = {
        body: data.body || '',
        icon: data.icon || '/notification-icon.png',
        data: data.data || {},
        actions: data.actions || [],
    };

    e.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (e) {
    const notification = e.notification;
    const action = e.action;
    const data = notification.data;

    if (action === 'view_activity') {
        clients.openWindow(data.url);
    } else {
        if (data.url) {
            clients.openWindow(data.url);
        }
    }
    notification.close();
});
