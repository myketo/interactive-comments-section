<template>
  <button class="comment-button" :class="'comment-button__' + action">
    <img
        :src="`/images/icon-${action}.svg`"
        :alt="action + ' icon'"
        class="button-icon"
        :class="'button-icon__' + action"
    />
    <span class="button-text" :class="'button-text__' + action">{{ action }}</span>
  </button>
</template>

<script>
export default {
  name: "CommentReply",

  props: {
    action: {
      required: true,
      type: String,
      validator(value) {
        return ['reply', 'delete', 'edit'].includes(value)
      },
    },
  },
}
</script>

<style lang="scss" scoped>
@import "/scss/_variables.scss";

.comment-button {
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

  .button-icon {
    margin-right: 0.5em;
  }

  .button-text {
    display: inline-block;
    color: $moderate-blue;
    font-weight: $font-weight-medium;

    &::first-letter {
      text-transform: uppercase;
    }

    &.button-text__delete {
      color: $soft-red;
    }
  }
}
</style>
