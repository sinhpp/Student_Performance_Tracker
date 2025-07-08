import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import { useUnifiedAuthStore } from './stores/unifiedAuth'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Initialize auth state before mounting
const authStore = useUnifiedAuthStore()
authStore.initializeAuth().then(() => {
  app.mount('#app')
})
