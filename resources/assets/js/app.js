require('./bootstrap');

// Vue.component('app-modal', require('./components/AppModal.vue'));
// app components
import App from './App.vue'
import Dashboard from './components/Dashboard.vue'
import Analytics from './components/Analytics.vue'
import Campaigns from './components/Campaigns.vue'
import CreateCampaign from './components/CreateCampaign.vue'
import WebConfig from './components/WebConfig.vue'
import Support from './components/Support.vue'

const router = new VueRouter()

router.map({
    '/dashboard': {
        component: Dashboard
    },
    '/analytics': {
        component: Analytics
    },
    '/campaigns': {
        component: Campaigns
    },
    '/create-campaigns': {
        component: CreateCampaign
    },
    '/web-config': {
        component: WebConfig
    },
    'support': {
        component: Support
    }
})

// Any invalid route will redirect to home
router.redirect({
    '*': '/dashboard'
})

router.start(App, '#app')
