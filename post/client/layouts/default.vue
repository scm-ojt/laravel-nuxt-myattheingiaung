<template>
  <div class="pb-4">
    <b-navbar toggleable="lg" type="dark" variant="info">
      <b-navbar-brand to="/">Post</b-navbar-brand>
      <b-navbar-nav class="ml-auto">
        <!-- <template>
            <b-nav-item to="/">Home</b-nav-item>
          </template> -->
        <template v-if="$auth.loggedIn">
          <b-nav-item to="/dashboard">Dashboard</b-nav-item>
          <b-nav-item to="/post">Post</b-nav-item>
          <b-nav-item href="#" @click.prevent="logout()">Logout</b-nav-item>
        </template>
        <template v-else>
          <b-nav-item to="/register">Register</b-nav-item>
          <b-nav-item to="/login">Login</b-nav-item>
        </template>
      </b-navbar-nav>
    </b-navbar>
    <Nuxt />
  </div>
</template>

<script>
export default {
  methods: {
    async logout() {
      try {
        await this.$auth.logout();
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>
