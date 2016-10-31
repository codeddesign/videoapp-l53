<template>
  <div>
    <div class="leftsidebar">
      <div class="logoarea">
        <div class="videologo"></div>
      </div>

      <ul class="navlist">
        <router-link :to="{ name: 'dashboard'}" tag="li">
          <a>
            <i class="fa fa-dashboard"></i> <span>DASHBOARD</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'analytics'}" tag="li">
          <a>
            <i class="fa fa-bar-chart"></i> <span>ANALYTICS</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'campaigns.listing'}" tag="li">
          <a>
            <i class="fa fa-newspaper-o"></i> <span>CAMPAIGNS</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'campaigns.create'}" tag="li">
          <a>
            <i class="fa fa-plus"></i> <span>CREATE CAMPAIGN</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'webconfig'}" tag="li">
          <a>
            <i class="fa fa-globe"></i> <span>WEBSITES</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'support'}" tag="li">
          <a>
            <i class="fa fa-flag"></i> <span>SUPPORT</span>
          </a>
        </router-link>
      </ul>

      <div class="sidebar-wpbutton">WORDPRESS PLUGIN</div>
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
</style>

<script>
  export default {
    name: 'Sidebar'
  }
</script>
