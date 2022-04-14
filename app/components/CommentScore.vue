<template>
  <div class="comment-score">
    <button class="score-btn score-increment">
      <img src="/images/icon-plus.svg" alt="Plus icon" />
    </button>

    <span class="current-score">{{ score }}</span>

    <button class="score-btn score-decrement">
      <img src="/images/icon-minus.svg" alt="Minus icon" />
    </button>
  </div>
</template>

<script>
export default {
  name: "CommentScore",

  props: {
    score: {
      type: Number,
      default: 0,
    },
  },
}
</script>

<style lang="scss" scoped>
  @import '/scss/_variables.scss';

  .comment-score {
    background-color: $very-light-gray;
    width: fit-content;
    padding: 0.5em;
    border-radius: 0.75em;
    display: flex;
    align-items: center;
    gap: 0.75em;

    .score-btn {
      border: none;
      background-color: inherit;
    }

    .current-score {
      color: $moderate-blue;
      font-weight: $font-weight-medium;
    }
  }
</style>
