self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const sendNotification = body => {
        return self.registration.showNotification('EGS', {
            body,
            icon: '/src/logo.png',
            image: '/src/logo.png',
            badge: '/src/logo.png'
        });
    };

    if (event.data) {
        const message = event.data.text();
        event.waitUntil(sendNotification(message));
    }
});
