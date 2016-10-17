import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

// App Components
import Dashboard from './views/dashboard/Dashboard.vue'
import Analytics from './views/analytics/Analytics.vue'
import Campaigns from './views/campaigns/Campaigns.vue'
import CreateCampaign from './views/campaigns/components/create.vue'
import ListCampaign from './views/campaigns/components/listing.vue'
import WebConfig from './views/webconfig/WebConfig.vue'
import Support from './views/support/Support.vue'

export default new Router({
  //mode: 'history',
  base: '/app',
  linkActiveClass: 'active',
  routes: [
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard
    },
    { 
      path: '/analytics',
      name: 'analytics',
      component: Analytics
    },
    {
      path: '/campaigns',
      name: 'campaigns',
      component: Campaigns,
      children: [
        {
          path: '/listing',
          name: 'campaigns.listing',
          component: ListCampaign
        },
        {
          path: '/create',
          name: 'campaigns.create',
          component: CreateCampaign
        }
      ]
    },
    {
      path: '/web-config',
      name: 'webconfig',
      component: WebConfig
    },
    {
      path: '/support',
      name: 'support',
      component: Support
    },

    //Redirects
    {
      path: '*',
      redirect: '/dashboard'
    },
    {
      path: '/campaigns',
      redirect: '/campaigns/listing'
    }
  ]
});
