import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.vapidPublicKey = process.env.MIX_VAPID_PUBLIC_KEY;
