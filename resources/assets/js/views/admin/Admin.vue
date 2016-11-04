<template>
  <div v-if="currentUser.isAdmin">
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    name: 'AdminArea',

    computed: {
      currentUser() {
        return this.$store.state.users.currentUser
      }
    },

    watch: {
      currentUser() {
        if (!this.currentUser.isAdmin) {
          this.$router.push({ name: 'dashboard' })
        }
      }
    }
  }
</script>
