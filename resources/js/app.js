// import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp, Link,Head } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
const pinia = createPinia()
import { Quasar } from 'quasar'
import quasarUserOptions from './quasar-user-options.js'
import 'quasar/dist/quasar.sass'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
      },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, quasarUserOptions)
      .use(pinia)
      .component('Link', Link)
      .component('Head', Head)
      .mount(el)
  },
})
