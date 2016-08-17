require('./bootstrap');

Vue.component('app-modal', require('./views/components/AppModal.vue'));

// app components
import App from './views/Layout.vue'
import Dashboard from './views/dashboard/Dashboard.vue'
import Analytics from './views/analytics/Analytics.vue'
import Campaigns from './views/campaigns/Campaigns.vue'
import CreateCampaign from './views/campaigns/CreateCampaign.vue'
import WebConfig from './views/webconfig/WebConfig.vue'
import Support from './views/support/Support.vue'

const router = new VueRouter({
    hashbang: false,
    history: true,
    root: '/app'
});

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
    '/support': {
        component: Support
    },
})


// Any invalid route will redirect to home
router.redirect({
    '*': '/dashboard'
})

router.start(App, '#app')
