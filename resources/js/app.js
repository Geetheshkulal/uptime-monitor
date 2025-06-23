// import './bootstrap';

import './test';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Add your Echo listener here
// document.addEventListener('DOMContentLoaded', () => {
//     if (typeof window.Echo === 'undefined') {
//         console.error('❌ Echo is not defined yet');
//         return;
//     }

//     console.log('✅ Echo is ready. Setting up listener...');

//     window.Echo.channel('global.notifications')
//         .listen('.new.global.notification', (e) => {
//             console.log('📣 New Notification Received:', e.notification);
//         });
// });