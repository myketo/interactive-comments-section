<template>
  <button class="comment-reply">
    <img src="/images/icon-reply.svg" alt="Reply icon" class="reply-icon" />
    <span class="reply-text">Reply</span>
  </button>
</template>

<script>
  export default {
    name: "CommentReply",
  }
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .comment-reply {
    border: none;
    background: none;

    > * {
      transition: filter 0.2s ease-in-out;
    }

    &:hover {
      > * {
        filter: opacity(0.33) drop-shadow(0 0 0 $light-grayish-blue);
      }
    }

    .reply-icon {
      margin-right: 0.5em;
    }

    .reply-text {
      color: $moderate-blue;
      font-weight: $font-weight-medium;
    }
  }
</style>
