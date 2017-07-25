<template>
  <div class="page-index">
    <div class="tagmanage-tabbed">
      <compare-tags></compare-tags>
      <compare-backfill></compare-backfill>
      <edit-tags :owned='false'></edit-tags>
      <edit-tags :owned='true'></edit-tags>
      <global-options></global-options>
    </div>
  </div>
</template>

<script>
  import EditTags from './EditTags.vue'
  import CompareTags from './CompareTags.vue'
  import CompareBackfill from './CompareBackfill.vue'
  import GlobalOptions from './GlobalOptions.vue'

  export default {
    name: 'TagManagement',
    components: {
      EditTags,
      CompareTags,
      CompareBackfill,
      GlobalOptions
    },

    mounted() {
      this.$store.dispatch('admin/loadGlobalOptions')
      this.$store.dispatch('admin/loadTags')
      this.$store.dispatch('admin/loadWebsites')
      this.$store.dispatch('admin/loadCountries')
    }
  }
</script>
