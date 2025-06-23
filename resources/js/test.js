
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Debugging
console.log("✅ test.js is loaded");

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: Number(import.meta.env.VITE_REVERB_PORT) || 8080,
    wssPort: Number(import.meta.env.VITE_REVERB_PORT) || 8080,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws'],
});

window.Echo.connector.pusher.connection.bind('connected', () => {
  console.log('✅ WebSocket connected via Reverb!');
});