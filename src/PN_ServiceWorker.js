self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return
    }

    const sendNotification = body => {
        const channel = new BroadcastChannel('sw-messages');
        channel.postMessage({title: body});

        return self.registration.showNotification('EGS', {
            body,
            icon: '/resources/EGS_Logo_outlined_black_v1.png',
            image: '/resources/EGS_Logo_outlined_black_v1.png',
            badge: '/resources/EGS_Logo_outlined_black_v1.png'
        })
    }

    if (event.data) {
        const message = event.data.text()
        event.waitUntil(sendNotification(message))
    }
})
