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
    import Sidebar from './components/Sidebar.vue'
    import RightsideNav from './components/RightsideNav.vue'

    export default {
        components: { Sidebar, RightsideNav }
    }
</script>
