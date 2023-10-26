import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import VueGoogleMaps from '@fawmi/vue-google-maps'

import BrandAmbassadorTracking from "./Pages/BrandAmbassador/BrandAmbassadorTracking.vue";

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(VueGoogleMaps, {
        load: {
            key: "AIzaSyAHv9dGlK4BtbyuVplUHLPJA4aQ4SjnWwA"
        }
      })
      .mount(el)
  },
})

// const app = createApp({});

// app.component("ba-tracking", BrandAmbassadorTracking)

