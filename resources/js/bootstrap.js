// Import Bootstrap using dynamic import
(async function() {
    try {
        const bootstrap = await import('bootstrap');
        window.bootstrap = bootstrap;
        console.log('Bootstrap loaded successfully:', bootstrap);
    } catch (error) {
        console.error('Failed to load Bootstrap:', error);
        // Fallback: try to load from CDN
        loadBootstrapFromCDN();
    }
})();

// Fallback function to load Bootstrap from CDN
function loadBootstrapFromCDN() {
    console.log('Loading Bootstrap from CDN...');

    // Load Bootstrap CSS
    if (!document.querySelector('link[href*="bootstrap"]')) {
        const bootstrapCSS = document.createElement('link');
        bootstrapCSS.rel = 'stylesheet';
        bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css';
        document.head.appendChild(bootstrapCSS);
    }

    // Load Bootstrap JS
    if (!window.bootstrap) {
        const bootstrapJS = document.createElement('script');
        bootstrapJS.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
        bootstrapJS.onload = function() {
            console.log('Bootstrap loaded from CDN successfully');
        };
        bootstrapJS.onerror = function() {
            console.error('Failed to load Bootstrap from CDN');
        };
        document.head.appendChild(bootstrapJS);
    }
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_HOST}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
