const subscribeBt = document.getElementById('subscribe-bt')
subscribeBt.addEventListener('click', async function () {
    await pnSubscribe()
})


function isPnAvailable() {
    let bAvailable = false;
    if (window.isSecureContext) {
        // running in secure context - check for available Push-API
        bAvailable = (('serviceWorker' in navigator) &&
            ('PushManager' in window) &&
            ('Notification' in window));
    } else {
        console.log('site have to run in secure context!');
    }
    return bAvailable;
}

async function pnSubscribe() {
    if (isPnAvailable()) {
        // if not granted or denied so far...
        if (window.Notification.permission === 'default') {
            await window.Notification.requestPermission();
        }
        if (Notification.permission === 'granted') {
            // register service worker
            await pnRegisterSW();
        }
    }
}

async function pnRegisterSW() {
    navigator.serviceWorker.register('PNServiceWorker.js')
        .then((swReg) => {
            // registration worked
            console.log('Registration succeeded. Scope is ' + swReg.scope);
        }).catch((e) => {
        // registration failed
        console.log('Registration failed with ' + e);
    });
}