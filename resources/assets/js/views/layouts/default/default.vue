<template>
  <div v-if="jwt">
    <sidebar></sidebar>

    <div class="rightside">
      <rightside-nav></rightside-nav>

      <router-view></router-view>
    </div>
    <div class="back-to-admin" v-if="impersonating" @click="stopImpersonating()">RETURN TO ADMIN</div>
  </div>
</template>

<script>
  import Sidebar from './../../components/Sidebar.vue'
  import RightsideNav from './../../components/RightsideNav.vue'

  export default {
    components: {
      Sidebar,
      RightsideNav
    },

    methods: {
      stopImpersonating() {
        this.$store.dispatch('users/impersonate', null)
      }
    },

    computed: {
      impersonating() {
        return this.$store.state.users.impersonating
      }
    },

    mounted() {
      this.$store.dispatch('users/loadUser')
    }
  }
</script>

<style lang="scss">
    $bootstrap-sass-asset-helper: true;
    @import "node_modules/bootstrap-sass/assets/stylesheets/bootstrap";

    @import "node_modules/eonasdan-bootstrap-datetimepicker/src/sass/bootstrap-datetimepicker-build";

    $fa-font-path: "~font-awesome/fonts";
    @import "~font-awesome/scss/font-awesome";

    @import "resources/assets/sass/style";

    .back-to-admin {
      position: fixed;
      bottom: 0;
      right: 0;
      margin: 0 18px 6px 0;
      padding: 10px;
      color: #EEEEEE;
      background: red;
      font-size: 12px;
      cursor: pointer; cursor: hand;
    }
</style>
