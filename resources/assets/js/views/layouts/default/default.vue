<template>
    <div>
        <sidebar></sidebar>

        <div class="rightside">
            <rightside-nav></rightside-nav>

            <router-view></router-view>
        </div>

    </div>

</template>
<script>
    import Sidebar from './../../components/Sidebar.vue'
    import RightsideNav from './../../components/RightsideNav.vue'
    export default {
        components: { Sidebar, RightsideNav }
    }
</script>
<style lang="scss">
    $bootstrap-sass-asset-helper: true;
    @import "node_modules/bootstrap-sass/assets/stylesheets/bootstrap";

    @import "node_modules/eonasdan-bootstrap-datetimepicker/src/sass/bootstrap-datetimepicker-build";

    $fa-font-path: "~font-awesome/fonts";
    @import "~font-awesome/scss/font-awesome";

    @import "resources/assets/sass/style";
</style>
