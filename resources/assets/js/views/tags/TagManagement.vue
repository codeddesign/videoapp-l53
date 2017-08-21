<template>
  <div class="page-index">
    <div class="tagmanage-tabbed">
      <compare-tags></compare-tags>
      <!--
      <compare-backfill></compare-backfill>-->
      <edit-tags :owned='false'></edit-tags>

    </div>
  </div>
</template>

<script>
  import EditTags from './EditTags.vue'
  import CompareTags from './CompareTags.vue'

  export default {
    name: 'TagManagement',
    components: {
      EditTags,
      CompareTags
    },

    mounted() {
      this.$store.dispatch('users/loadTags')
      this.$store.dispatch('users/loadWebsites')
      this.$store.dispatch('users/loadLocations')
    }
  }
</script>
