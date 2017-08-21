<template>
  <div>
    <div class="leftsidebar">
      <div class="logoarea">
        <div class="videologo"></div>
      </div>

      <!-- User Menu -->
      <div v-if="! currentUser.isAdmin">

        <ul class="navlist">
          <router-link :to="{ name: 'dashboard'}">
            <li>
              <span>DASHBOARD</span>
            </li>
          </router-link>

          <!--<router-link :to="{ name: 'analytics'}">
            <li>
              <span>ANALYTICS</span>
            </li>
          </router-link>-->

          <router-link :to="{ name: 'campaigns.listing'}">
            <li>
              <span>CAMPAIGNS</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'campaigns.create'}">
            <li>
              <span>CREATE CAMPAIGN</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'tagmanagement'}">
            <li>
              <span>TAG MANAGEMENT</span>
            </li>
          </router-link>

         <router-link :to="{ name: 'reports'}">
            <li>
              <span>REPORTS</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'webconfig'}">
            <li>
              <span>WEBSITES</span>
            </li>
          </router-link>

          <!--<router-link :to="{ name: 'support'}">
            <li>
              <span>SUPPORT</span>
            </li>
          </router-link>-->
        </ul>
        <!--<div class="sidebar-wpbutton">WORDPRESS PLUGIN</div>-->
      </div>

      <!-- Admin Menu -->
      <div v-else>
        <ul class="navlist">
          <router-link :to="{ name: 'admin.dashboard'}">
            <li>
              <span>DASHBOARD</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'admin.accounts'}">
            <li>
              <span>ACCOUNTS</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'admin.reports'}">
            <li>
              <span>REPORTS</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'admin.tagmanagement'}">
            <li>
              <span>TAG MANAGEMENT</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'admin.analytics'}">
            <li>
              <span>ANALYTICS</span>
            </li>
          </router-link>

          <router-link :to="{ name: 'admin.settings'}">
            <li>
              <span>SETTINGS</span>
            </li>
          </router-link>
        </ul>
      </div>

    </div>
  </div>
</template>

<style lang="scss">
  .sidebar-wpbutton {
    float: left;
    width: 160px;
    height: 44px;
    text-align: center;
    line-height: 43px;
    color: #FFFFFF;
    font-size: 12px;
    background: #02A3DE;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    margin-left: 13px;
    margin-top: 40px;
    cursor: pointer;
    letter-spacing: 0.3px;

    :hover {
      background: #0090C6;
    }
  }

  a.active li {
    background: #6F6BA2;
  }
</style>

<script>
  export default {
    name: 'Sidebar',

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      }
    }
  }
</script>
