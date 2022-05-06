<template>
  <textarea
      class="comment-textarea"
      @input="mixinTextareaAutoResize"
      ref="textarea"
      v-model="modelValue"
  ></textarea>
</template>

<script>
  import mixinTextareaAutoResize from "../mixins/textareaAutoResize";

  export default {
    name: "CommentTextarea",

    mixins: [
      mixinTextareaAutoResize,
    ],

    props: [
      "content",
      "modelValue",
      "ref",
    ],

    emits: [
      "update:modelValue",
    ],

    updated() {
      this.$refs.textarea.dispatchEvent(new Event("input"))
      this.$refs.textarea.focus()
    },
  }
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .comment-textarea {
    width: 100%;
    height: fit-content;
    overflow-y: hidden;
    padding: 0.75em 1em;
    border-color: $grayish-blue;
    border-radius: 0.5em;
    resize: none;
    outline: none;
    color: $dark-blue;

    &:focus-visible {
      border-color: $dark-blue;
    }
  }
</style>