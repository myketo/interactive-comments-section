<template>
  <div class="big-button-container">
    <button class="big-button" :class="`big-button__${action}`" @click="$emit(`${action}Comment`)">
      {{ action === 'edit' ? 'update' : action }}
    </button>
  </div>
</template>

<script>
  export default {
    name: "BigButton",

    props: {
      action: {
        required: true,
        type: String,
        validator(value) {
          return ['reply', 'send', 'edit'].includes(value)
        },
      },
    },
  }
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .big-button-container {
    width: 100%;
    text-align: end;
    margin-top: 0.5em;

    @media (min-width: $min-desktop) {
      width: auto;
      margin-top: 0;
    }

    .big-button {
      border: none;
      border-radius: 0.5em;
      background-color: $moderate-blue;
      color: $white;
      padding: 0.65em 1.25em;
      text-transform: uppercase;
      font-weight: $font-weight-medium;
      transition: filter 0.2s ease-in-out;

      &.big-button__send {
        padding: 0.85em 2em;
      }

      &:hover {
        filter: opacity(0.33) drop-shadow(0 0 0 $light-grayish-blue);
      }
    }
  }
</style>