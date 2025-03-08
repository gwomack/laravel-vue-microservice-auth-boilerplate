import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy'

// Initialize dark mode
if (localStorage.getItem('darkMode') === 'dark' || 
    (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}

createInertiaApp({
  resolve: async name => {
    const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
    return page
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    
    app.use(plugin)
    app.use(ZiggyVue)
    app.component('Link', Link)
    app.mount(el)
  },
  progress: {
    color: '#4B5563',
  },
}).catch(e => {
    console.error('Inertia setup error:', e)
})