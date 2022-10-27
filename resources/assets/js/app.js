
import { routes } from "./route"
import NotFound from '../components/NotFound.vue'

/** import pinia generalStore*/
import { useGeneral } from './pinia/general'

export default {
    name: "App",
    data() {
        return {
            modalAboutMe: false,
            message: 'Hello Vue!',
            menu: [
                //     {
                //     route: '/',
                //     icon: 'fa fa-dashboard',
                //     content: 'GENERAL.dashboard',
                // },
                {
                    route: '/home',
                    icon: 'fa fa-home',
                    content: 'GENERAL.home',
                },
                {
                    route: '/answered',
                    icon: 'fa fa-microphone',
                    content: 'GENERAL.answered',
                },
                {
                    route: '/unAnswered',
                    icon: 'fa fa-microphone-slash',
                    content: 'GENERAL.unAnswered',
                },
                {
                    route: '/distribution',
                    icon: 'fa fa-calendar-o',
                    content: 'GENERAL.distribution',
                },
                {
                    route: '/operator',
                    icon: 'fa fa-users',
                    content: 'GENERAL.operator',
                },
                {
                    route: '/search',
                    icon: 'fa fa-search',
                    content: 'GENERAL.search',
                },
                // {
                //     route: '/realTime',
                //     icon: 'fa fa-link',
                //     content: 'GENERAL.realTime',
                // },
                {
                    route: '/setting',
                    icon: 'fa fa-cogs',
                    content: 'GENERAL.setting',
                }]


        }
    },
    methods: {
        refreshPage() {
            document.getElementById("refresh").click();
        }
    },
    computed: {
        currentView() {
            return routes[this.general.route.slice(1) || '/'] || NotFound
        }
    },
    mounted() {

        this.$i18n.locale = localStorage.getItem('lang');

        window.addEventListener('hashchange', () => {
            this.currentPath = window.location.hash
        })
    },
    setup() {
        const general = useGeneral()
        return {
            general,
        }
    }
}