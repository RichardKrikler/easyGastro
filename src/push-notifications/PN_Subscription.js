document.addEventListener('DOMContentLoaded', () => {
    const applicationServerKey =
        'BJvFyEJTb975woE0mQf6jCA7bJEdbAZ3nuT7Ex_I1KjWrDBKYwrfmH7dcMjseRBRoNfVZrgBb_rzTFLwvTyggHQ'
    let isPushEnabled = false

    const pushButton = document.getElementById('push-subscription-button')
    if (pushButton == null) {
        return
    }
    pushButton.addEventListener('click', function () {
        if (isPushEnabled) {
            push_unsubscribe()
        } else {
            push_subscribe()
        }
    })

    const logoutButton = document.getElementById('logoutBt')
    if (logoutButton !== null) {
        logoutButton.addEventListener('click', function () {
            if (isPushEnabled) {
                push_unsubscribe()
            }
        })
    }

    if (!('serviceWorker' in navigator) ||
        !('PushManager' in window) ||
        !('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.warn('Push Notifications incompatible with browser')
        changePushButtonState('incompatible')
        return
    }

    // Check the current Notification permission.
    // If its denied, the button should appears as such, until the user changes the permission manually
    if (Notification.permission === 'denied') {
        console.warn('Notifications are denied by the user')
        changePushButtonState('incompatible')
        return
    }

    navigator.serviceWorker.register('/PN_ServiceWorker.js').then(
        () => {
            console.log('[SW] Service worker has been registered')
            push_updateSubscription()
        },
        e => {
            console.error('[SW] Service worker registration failed', e)
            changePushButtonState('incompatible')
        }
    )

    function changePushButtonState(state) {
        switch (state) {
            case 'enabled':
                pushButton.disabled = false
                pushButton.textContent = 'Benachrichtigungen deaktivieren'
                pushButton.classList.replace('bg-red', 'bg-green')
                isPushEnabled = true
                break
            case 'disabled':
                pushButton.disabled = false
                pushButton.textContent = 'Benachrichtigungen aktivieren'
                pushButton.classList.replace('bg-green', 'bg-red')
                isPushEnabled = false
                break
            case 'computing':
                pushButton.disabled = true
                pushButton.textContent = 'Loading...'
                break
            case 'incompatible':
                pushButton.disabled = true
                pushButton.textContent = 'Benachrichtigungen sind nicht mit diesem Browser kompatibel'
                pushButton.classList.replace('bg-green', 'bg-red')
                break
            default:
                console.error('Unhandled push button state', state)
                break
        }
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/')

        const rawData = window.atob(base64)
        const outputArray = new Uint8Array(rawData.length)

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i)
        }
        return outputArray
    }

    function checkNotificationPermission() {
        return new Promise((resolve, reject) => {
            if (Notification.permission === 'denied') {
                return reject(new Error('Push messages are blocked.'))
            }

            if (Notification.permission === 'granted') {
                return resolve()
            }

            if (Notification.permission === 'default') {
                return Notification.requestPermission().then(result => {
                    if (result !== 'granted') {
                        reject(new Error('Bad permission result'))
                    } else {
                        resolve()
                    }
                })
            }

            return reject(new Error('Unknown permission'))
        })
    }

    function push_subscribe() {
        return checkNotificationPermission()
            .then(() => navigator.serviceWorker.ready)
            .then(serviceWorkerRegistration =>
                serviceWorkerRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(applicationServerKey),
                })
            )
            .then(subscription => {
                // Subscription was successful
                // create subscription on your server
                return push_sendSubscriptionToServer(subscription, 'POST')
            })
            .then(subscription => subscription && changePushButtonState('enabled')) // update your UI
            .catch(e => {
                if (Notification.permission === 'denied') {
                    // User denied -> permission has to be manually changed
                    console.warn('Notifications are denied by the user.')
                    changePushButtonState('incompatible')
                } else {
                    // Subscription was not possible
                    console.error('Impossible to subscribe to push notifications', e)
                    changePushButtonState('disabled')
                }
            })
    }

    function push_updateSubscription() {
        navigator.serviceWorker.ready
            .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
            .then(subscription => {
                changePushButtonState('disabled')

                if (!subscription) {
                    // We aren't subscribed to push, so set UI to allow the user to enable push
                    return
                }

                // Keep your server in sync with the latest endpoint
                return push_sendSubscriptionToServer(subscription, 'PUT')
            })
            .then(subscription => subscription && changePushButtonState('enabled')) // Set your UI to show they have subscribed for push messages
            .catch(e => {
                console.error('Error when updating the subscription', e)
            })
    }

    function push_unsubscribe() {
        navigator.serviceWorker.ready
            .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
            .then(subscription => {
                // check availability of subscription to unsubscribe
                if (!subscription) {
                    // no subscription available -> disabled
                    changePushButtonState('disabled')
                    return
                }

                // subscription available -> unsubscribe & remove from server
                return push_sendSubscriptionToServer(subscription, 'DELETE')
            })
            .then(subscription => subscription.unsubscribe())
            .then(() => changePushButtonState('disabled'))
            .catch(e => {
                // failed to unsubscribe
                console.error('Error when unsubscribing the user', e)
                changePushButtonState('disabled')
            })
    }

    function push_sendSubscriptionToServer(subscription, method) {
        const key = subscription.getKey('p256dh')
        const token = subscription.getKey('auth')
        const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]

        return fetch('/push-notifications/PN_Subscriber.php', {
            method,
            body: JSON.stringify({
                endpoint: subscription.endpoint,
                publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                contentEncoding,
                userId: userId
            }),
        }).then(() => subscription)
    }
})
