<template>
  <div class="comments-section">
    <comment
      v-for="comment in comments"
      :key="comment.id"
      :data="comment"
      @commentAdded="loadComments">
    </comment>
  </div>
</template>

<script>
import api from "../api";
import Comment from "./Comment";

export default {
  name: "CommentsSection",

  components: {
    Comment,
  },

  data() {
    return {
      comments: [],
    }
  },

  methods: {
    async loadComments() {
      this.comments = await api.helpGet('comments')
    },
  },

  beforeMount() {
    this.loadComments()
  }
}
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .comments-section {
    background-color: $very-light-gray;
    padding: 2em 1em;
  }
</style>