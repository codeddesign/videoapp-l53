require('./bootstrap');

Vue.component('app-modal', require('./views/components/AppModal.vue'));

// app components
// import App from './views/Layout.vue'
import App from './views/layouts/default/default.vue'
import Dashboard from './views/dashboard/Dashboard.vue'
import Analytics from './views/analytics/Analytics.vue'
import Campaigns from './views/campaigns/Campaigns.vue'
import CreateCampaign from './views/campaigns/components/create.vue'
import ListCampaign from './views/campaigns/components/listing.vue'
import WebConfig from './views/webconfig/WebConfig.vue'
import Support from './views/support/Support.vue'

/**
 * if we want to remove hashbang from URL,
 * switch it to false, and history to true.
 * make sure you configure the the server
 * to handle it.
 */
const router = new VueRouter({
    hashbang: true,
    history: false,
    root: '/app',
    linkActiveClass: 'active', // this is used to set the active links
});

router.map({
    '/dashboard': {
        name: 'dashboard',
        component: Dashboard
    },
    '/analytics': {
        name: 'analytics',
        component: Analytics
    },
    '/campaigns': {
        name: 'campaigns',
        component: Campaigns,
        subRoutes: {
            '/listing': {
                name: 'campaigns.listing',
                component: ListCampaign
            },
            '/create': {
                name: 'campaigns.create',
                component: CreateCampaign
            }
        }
    },
    '/web-config': {
        name: 'webconfig',
        component: WebConfig
    },
    '/support': {
        name: 'support',
        component: Support
    },
})


// Any invalid route will redirect to home
router.redirect({
    '*': '/dashboard',
    '/campaigns': '/campaigns/listing'
})

router.start(App, '#app')
