import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;



console.log('Pusher key:', import.meta.env.VITE_PUSHER_APP_KEY);
console.log('Pusher cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);
console.log('Pusher encrypted:', import.meta.env.VITE_PUSHER_APP_ENCRYPTED);


const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

export default echo;


