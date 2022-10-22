import { createApp } from "vue"

import Home from '../components/Home.vue'
import About from '../components/About.vue'
import NotFound from '../components/NotFound.vue'

const routes = {
    '/': Home,
    '/about': About
}

export const app = createApp({
    data() {
        return {
            message: 'Hello Vue!',
            currentPath: window.location.hash
        }
    },
    computed: {
        currentView() {
            return routes[this.currentPath.slice(1) || '/'] || NotFound
        }
    },
    mounted() {
        window.addEventListener('hashchange', () => {
            this.currentPath = window.location.hash
        })
    }
}).mount('#app')