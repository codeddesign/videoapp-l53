<template>
  <div></div>
</template>

<script>
  import auth from '../../services/auth'

  export default {
    mounted() {
      auth.logout()
    }
  }
</script>
