/**
 * Main application entry point
 * 
 * This file bootstraps the Vue application with Inertia.js integration
 * and initializes core features like dark mode support.
 */

import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy';

/**
 * Dark Mode Initialization
 * Checks local storage and system preferences to set initial dark mode state
 */
if (localStorage.getItem('darkMode') === 'dark' || 
    (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

/**
 * Inertia Application Setup
 * Creates and configures the Vue application with Inertia integration
 */
createInertiaApp({
    resolve: async (name: string) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        app.use(plugin);
        app.use(ZiggyVue);
        app.component('Link', Link);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).catch((e: Error) => {
    console.error('Inertia setup error:', e);
});
