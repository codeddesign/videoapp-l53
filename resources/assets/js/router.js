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
import Logout from './views/components/Logout.vue'

// Admin Componentes
import AdminDashboard from './views/admin/Dashboard.vue'

export default new Router({
  mode: 'history',
  base: '/app',
  linkActiveClass: 'active',
  routes: [
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      meta: {
        title: 'Dashboard'
      }
    }, {
      path: '/analytics',
      name: 'analytics',
      component: Analytics,
      meta: {
        title: 'Analytics'
      }
    }, {
      path: '/campaigns',
      name: 'campaigns',
      component: Campaigns,
      children: [
        {
          path: 'listing',
          name: 'campaigns.listing',
          component: ListCampaign,
          meta: {
            title: 'Your Campaigns'
          }
        }, {
          path: 'create',
          name: 'campaigns.create',
          component: CreateCampaign,
          meta: {
            title: 'Create Campaign'
          }
        }
      ]
    }, {
      path: '/web-config',
      name: 'webconfig',
      component: WebConfig,
      meta: {
        title: 'Web Config'
      }
    }, {
      path: '/support',
      name: 'support',
      component: Support,
      meta: {
        title: 'Support'
      }
    }, {
      path: '/admin',
      name: 'admin.dashboard',
      component: AdminDashboard
    }, {
      path: '/logout',
      name: 'logout',
      component: Logout,
    },

    // Redirects
    {
      path: '*',
      redirect: '/dashboard'
    }, {
      path: '/campaigns',
      redirect: '/campaigns/listing'
    }
  ]
})
