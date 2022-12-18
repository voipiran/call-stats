
import dash from '../components/Dashboard.vue'
import Home from '../components/Home.vue'
import About from '../components/About.vue'
import Answered from '../components/Answered.vue'
import UnAnswered from '../components/UnAnswered.vue'
import Distribution from '../components/Distribution.vue'
import Operator from '../components/Operator.vue'
import Search from '../components/Search.vue'
import Setting from '../components/SettingComponent.vue'
import RealTime from '../components/RealTime.vue'


export const routes = {
    // '/': dash,
    '/': Home,
    'home': Home,
    'answered': Answered,
    'unAnswered': UnAnswered,
    'distribution': Distribution,
    'operator': Operator,
    'setting': Setting,
    'realTime': RealTime,
    'search': Search,
    '/about': About
}

// export const routes = {
//     '/': () => Home,
//     'home': () => Home,
//     'answered': () => import('../components/Answered.vue'),
//     'unAnswered': () => import('../components/UnAnswered.vue'),
//     'distribution': () => import('../components/Distribution.vue'),
//     'operator': () => import('../components/Operator.vue'),
//     'setting': () => import('../components/SettingComponent.vue'),
//     'realTime': () => import('../components/RealTime.vue'),
//     'search': () => import('../components/Search.vue'),
//     '/about': () => import('../components/About.vue')
// }