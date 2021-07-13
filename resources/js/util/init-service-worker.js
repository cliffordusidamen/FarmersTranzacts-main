if ("serviceWorker" in navigator) {
    navigator.serviceWorker
        .register("/sw.js", { scope: "/" })
        .then((res) => initServiceWorker())
        .catch((err) => console.log("service worker not registered", err));

    function initServiceWorker() {
        if (!("showNotification" in ServiceWorkerRegistration.prototype)) {
            console.log("Notifications aren't supported.");
            return;
        }

        if (Notification.permission === "denied") {
            console.log("The user has blocked notifications.");
            return;
        }

        if (!("PushManager" in window)) {
            console.log("Push messaging isn't supported.");
            return;
        }

        navigator.serviceWorker.ready.then((registration) => {
            registration.pushManager
                .getSubscription()
                .then((subscription) => {
                    if (!subscription) {
                        return;
                    }

                    console.log('Has previous subscription!');

                    updateNotificationSubscription(subscription);
                })
                .catch((e) => {
                    console.log("Error during getSubscription()", e);
                });
        });
    }

    window.updateNotificationSubscription = function (subscription) {
        console.log("Updating subscription...");
        const key = subscription.getKey("p256dh");
        const token = subscription.getKey("auth");
        const contentEncoding = (PushManager.supportedContentEncodings || [
            "aesgcm",
        ])[0];

        const data = {
            endpoint: subscription.endpoint,
            publicKey: key
                ? btoa(String.fromCharCode.apply(null, new Uint8Array(key)))
                : null,
            authToken: token
                ? btoa(
                        String.fromCharCode.apply(null, new Uint8Array(token))
                    )
                : null,
            contentEncoding,
        };

        axios
            .post("/subscription/update", data)
            .then(() => console.log("Subscription successful!"))
            .catch((e) => console.log("Subscription update failed!", e));
    };

    window.subscribeForNotification = function () {
        navigator.serviceWorker.ready.then((registration) => {
            const options = { userVisibleOnly: true };
            const vapidPublicKey = process.env.MIX_VAPID_PUBLIC_KEY;

            if (vapidPublicKey) {
                options.applicationServerKey =
                    urlBase64ToUint8Array(vapidPublicKey);
            }

            registration.pushManager
                .subscribe(options)
                .then((subscription) => {
                    updateNotificationSubscription(subscription);
                })
                .catch((e) => {
                    if (Notification.permission === "denied") {
                        console.log(
                            "Permission for Notifications was denied"
                        );
                    } else {
                        console.log("Unable to subscribe to push.", e);
                    }
                });
        });
    };

    /**
     * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
     *
     * @param  {String} base64String
     * @return {Uint8Array}
     */
    window.urlBase64ToUint8Array = function (base64String) {
        const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, "+")
            .replace(/_/g, "/");

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    };
}
