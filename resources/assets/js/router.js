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

// Admin Components
import Admin from './views/admin/Admin.vue'
import AdminDashboard from './views/admin/Dashboard.vue'
import AdminAccounts from './views/admin/Accounts.vue'
import AccountInfo from './views/admin/AccountInfo.vue'
import TagManagement from './views/admin/tags/TagManagement.vue'
import Reports from './views/admin/reports/Reports.vue'
import CreateReport from './views/admin/reports/CreateReport.vue'
import Settings from './views/admin/Settings.vue'

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
      path: '/logout',
      name: 'logout',
      component: Logout
    }, {
      path: '/admin',
      name: 'admin',
      component: Admin,
      children: [
        {
          path: 'dashboard',
          name: 'admin.dashboard',
          component: AdminDashboard,
          meta: {
            title: 'Admin Dashboard'
          }
        }, {
          path: 'accounts',
          name: 'admin.accounts',
          component: AdminAccounts,
          meta: {
            title: 'Manage Accounts'
          }
        },
        {
          path: 'accounts/:accountId',
          name: 'admin.accounts.info',
          component: AccountInfo,
          meta: {
            title: 'Account Information'
          }
        },
        {
          path: 'tags',
          name: 'admin.tagmanagement',
          component: TagManagement,
          meta: {
            title: 'Manage Tags'
          }
        },
        {
          path: 'reports',
          name: 'admin.reports',
          component: Reports,
          meta: {
            title: 'Reports'
          }
        },
        {
          path: 'reports/create',
          name: 'admin.reports.create',
          component: CreateReport,
          meta: {
            title: 'Create Report'
          }
        },
        {
          path: 'settings',
          name: 'admin.settings',
          component: Settings,
          meta: {
            title: 'Settings'
          }
        }
      ]
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
