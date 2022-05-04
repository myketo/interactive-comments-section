<template>
  <div class="big-button-container">
    <button class="big-button" @click="$emit(`${action}Comment`)">
      {{ action }}
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
          return ['reply', 'delete', 'edit'].includes(value)
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

    .big-button {
      border: none;
      border-radius: 0.5em;
      background-color: $moderate-blue;
      color: $white;
      padding: 0.65em 1.25em;
      text-transform: uppercase;
      font-weight: $font-weight-medium;
    }
  }
</style>