<template>
    <div>
        <button @click="requestPermission" class="btn btn-primary"
                :disabled="isPushEnabled || isLoading">
            {{ buttonText }}
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isPushEnabled: false,
            isLoading: false
        };
    },
    computed: {
        buttonText() {
            if (this.isLoading) return 'Processing...';
            return this.isPushEnabled ? 'Notifications Enabled' : 'Enable Notifications';
        }
    },
    mounted() {
        this.checkPermission();
    },
    methods: {
        async checkPermission() {
            // Check if service worker is supported
            if (!('serviceWorker' in navigator)) {
                console.warn('Service workers are not supported by this browser');
                return;
            }

            // Check if Push API is supported
            if (!('PushManager' in window)) {
                console.warn('Push API is not supported by this browser');
                return;
            }

            // Check if notification permission is already granted
            if (Notification.permission === 'granted') {
                this.isPushEnabled = true;
            }
        },
        async requestPermission() {
            this.isLoading = true;

            try {
                // Request permission
                const permission = await Notification.requestPermission();

                if (permission !== 'granted') {
                    console.warn('Notification permission denied');
                    this.isLoading = false;
                    return;
                }

                // Register service worker
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker registered');

                // Subscribe to push notifications
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: this.urlBase64ToUint8Array(process.env.MIX_VAPID_PUBLIC_KEY)
                });

                // Send subscription to the server
                await this.sendSubscriptionToServer(subscription);

                this.isPushEnabled = true;

            } catch (error) {
                console.error('Error enabling notifications:', error);
            }

            this.isLoading = false;
        },
        async sendSubscriptionToServer(subscription) {
            await axios.post('/push-subscriptions', {
                endpoint: subscription.endpoint,
                keys: {
                    p256dh: btoa(String.fromCharCode.apply(null,
                        new Uint8Array(subscription.getKey('p256dh')))),
                    auth: btoa(String.fromCharCode.apply(null,
                        new Uint8Array(subscription.getKey('auth'))))
                }
            });
        },
        urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }

            return outputArray;
        }
    }
};
</script>
