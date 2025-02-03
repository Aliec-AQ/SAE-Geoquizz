// CSS
import '@/assets/main.css'
import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import "vue-toastification/dist/index.css";
import "leaflet/dist/leaflet.css";

//plugins
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persist'
import api from '@/plugins/api'
import Toast from "vue-toastification";
import App from '@/App.vue'
import router from '@/router'

const app = createApp(App)

//setup api
app.use(api, {
    baseURL: 'http://localhost:3000',
})

//setup pinia
const pinia = createPinia()
pinia.use(piniaPersist)
pinia.use(({ store }) => {
    store.$api = app.config.globalProperties.$api;
});

app.use(Toast,{
    position: "bottom-right"
})
app.use(pinia)
app.use(router)

app.mount('#app')
