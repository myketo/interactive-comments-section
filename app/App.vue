<template>
  <comments-section></comments-section>
</template>

<script>
import api from "./api";
import CommentsSection from "./components/CommentsSection";

export default {
  name: "App",

  components: {
    CommentsSection,
  },

  methods: {
    // Finds user with selected ID and stores his data as currentUser in local storage.
    async storeCurrentUser() {
      const currentUserId = 1
      const currentUser = await api.helpGet(`users/${currentUserId}`)

      localStorage.currentUser = JSON.stringify(currentUser)
    }
  },

  async beforeMount() {
    await this.storeCurrentUser()
  },
}
</script>

<style lang="scss">
  @import "/scss/_variables.scss";

  // Default values
  * {
    font-family: 'Rubik', sans-serif;
    font-weight: $font-weight-normal;
  }

  p {
    margin-bottom: 0;
  }
</style>