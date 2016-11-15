<template>
  <div class="page-index">
    <div class="tagmanage-tabbed">
      <compare-tags></compare-tags>
      <edit-tags></edit-tags>
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
    }
  }
</script>
