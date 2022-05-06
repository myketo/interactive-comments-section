<template>
  <div class="modal-backdrop">
    <div class="delete-comment-modal">
      <h2 class="modal-title">Delete comment</h2>
      <p class="modal-text">
        Are you sure you want to delete this comment?
        This will remove the comment and can't be undone.
      </p>
      <div class="modal-buttons">
        <button class="cancel-button" @click="$emit('cancelDelete')">NO, CANCEL</button>
        <button class="confirm-button" @click="$emit('confirmDelete')">YES, DELETE</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "DeleteCommentModal",
  }
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .modal-backdrop {
    background-color: hsla(0, 0%, 0%, 0.5);
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;

    .delete-comment-modal {
      background: $white;
      padding: 1.65em 1.75em;
      margin: 1em;
      border-radius: 0.5em;

      .modal-title {
        color: $dark-blue;
        font-size: 21px;
        margin-bottom: 0.5em;
      }

      .modal-text {
        color: $grayish-blue;
        margin: 1.15em 0;
      }

      .modal-buttons {
        display: flex;
        justify-content: space-between;

        button {
          border: none;
          border-radius: 0.5em;
          color: $white;
          font-weight: $font-weight-medium;
          padding: 0.75em 1.25em;

          &.cancel-button {
            background-color: $grayish-blue;
          }

          &.confirm-button {
            background-color: $soft-red;
          }
        }
      }
    }
  }
</style>
