import { createApp } from "vue"
import { createI18n } from 'vue-i18n'
import { createPinia } from 'pinia'

import App from './app.js'

/**axios instance */
import axiosInstance from "./axios"

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)

app.config.globalProperties.$axios = axiosInstance

// import Notifications
import Notifications from '@kyvg/vue3-notification'
app.use(Notifications)

import langI18n from './I18_localization/langI18n.js'
const i18n = createI18n(langI18n)
app.use(i18n)


app.mount('#app')


