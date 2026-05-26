import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'

import { plugin as VueTippy } from 'vue-tippy'
import 'tippy.js/dist/tippy.css'

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import es from 'element-plus/es/locale/lang/es'
import router from './router'

const app = createApp(App)

app.use(VueTippy, {
    directive: 'tippy',
    component: 'tippy',
    defaultProps: {
        theme: 'dark',
    },
})

app.use(createPinia())
app.use(ElementPlus, {
    locale: es,
})
app.use(router)
app.mount('#app')
