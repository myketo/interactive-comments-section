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

  data() {
    return {
      currentUser: Object,
    }
  },

  async beforeMount() {
    if (localStorage.currentUser) {
      this.currentUser = JSON.parse(localStorage.currentUser)
    } else {
      const currentUserId = 1

      this.currentUser = await api.helpGet(`users/${currentUserId}`)
      localStorage.currentUser = JSON.stringify(this.currentUser)
    }
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