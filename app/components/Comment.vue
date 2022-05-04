<template>
  <div class="comment-container" :class="{'is-response': isResponse()}">
    <div class="comment">
      <div class="comment__top-section">
        <img :src="getImage()" :alt="getImageAlt()" class="user-avatar" />
        <p class="username">{{ data.user.username }}</p>
        <span class="is-you" v-if="isCurrentUser()">you</span>
        <p class="created_time_ago">{{ getCreatedTimeAgo() }}</p>
      </div>

      <div class="comment__middle-section">
        <p class="message" v-show="!showEditCommentField">
          <span class="response-to" v-if="isResponse()">@{{ data.parent_comment_author }}</span>
          {{ data.content }}
        </p>
        <textarea class="message-edit" @input="mixinTextareaAutoResize" ref="commentEdit" v-show="showEditCommentField">@{{ data.parent_comment_author }} {{ data.content }}</textarea>
        <big-button action="edit" v-show="showEditCommentField" @editComment="editComment()"></big-button>
      </div>

      <div class="comment__bottom-section">
        <comment-score :score="data.score"></comment-score>

        <comment-button action="reply" v-if="!isCurrentUser()"></comment-button>
        <div class="current-user-buttons" v-else>
          <comment-button action="delete"></comment-button>
          <comment-button action="edit" @showEditComment="toggleShowEditCommentField()"></comment-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CommentScore from './CommentScore';
import CommentButton from './CommentButton';
import BigButton from "./BigButton";

import api from "../api";
import mixinTextareaAutoResize from '../mixins/textareaAutoResize';

import TimeAgo from 'javascript-time-ago';
import en from 'javascript-time-ago/locale/en';
TimeAgo.addDefaultLocale(en)
const timeAgo = new TimeAgo('en-US')

export default {
  name: "Comment",

  mixins: [
      mixinTextareaAutoResize,
  ],

  props: {
    data: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      showEditCommentField: false,
    }
  },

  components: {
    CommentScore,
    CommentButton,
    BigButton,
  },

  methods: {
    getImage() {
      return `../images/avatars/image-${this.data.user.username}.webp`;
    },
    getImageAlt() {
      return `${this.data.user.username}'s avatar`;
    },
    getCreatedTimeAgo() {
      return timeAgo.format(new Date(this.data.created_at))
    },
    isCurrentUser() {
      const currentUser = JSON.parse(localStorage.currentUser)

      return currentUser.id === this.data.user.id
    },
    isResponse() {
      return this.data.parent_comment_id !== null
    },
    toggleShowEditCommentField() {
      this.showEditCommentField = !this.showEditCommentField
    },
    editComment() {
      const newContent = this.removeFirstWord(this.$refs.commentEdit.value)

      if (newContent !== this.data.content) {
        api.helpPatch(`comments/${this.data.id}`, { content: newContent })

        this.data.content = newContent
      }

      this.toggleShowEditCommentField()
    },
    removeFirstWord(string) {
      if (!this.data.parent_comment_id) {
        return string;
      }

      const indexOfSpace = string.indexOf(' ');
      if (indexOfSpace === -1) {
        return '';
      }

      return string.substring(indexOfSpace + 1);
    }
  },

  updated() {
    if (this.showEditCommentField) {
      this.$refs.commentEdit.dispatchEvent(new Event("input"))
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .comment-container {
    padding-bottom: 1em;

    &.is-response {
      border-left: 2px solid $light-gray;
      padding-left: 1em;
    }

    .comment {
      background-color: $white;
      width: 100%;
      padding: 1em;
      border-radius: 0.33em;

      .comment__top-section {
        display: flex;
        align-items: center;
        gap: 1em;

        .user-avatar {
          width: 2em;
          min-width: 2em;
          height: fit-content;
        }

        .username {
          font-weight: $font-weight-medium;
        }

        .is-you {
          background-color: $moderate-blue;
          color: $white;
          border-radius: 2px;
          padding: 0 0.55em 0.05em 0.55em;
          font-weight: $font-weight-medium;
          margin-left: -0.4em;
        }

        .created_time_ago {
          color: $grayish-blue;
        }
      }

      .comment__middle-section {
        margin: 1em 0;

        .message {
          color: $grayish-blue;

          .response-to {
            color: $moderate-blue;
            font-weight: $font-weight-medium;
          }
        }

        .message-edit {
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
      }

      .comment__bottom-section {
        display: flex;
        justify-content: space-between;
        align-items: center;

        .current-user-buttons {
          display: flex;
        }
      }
    }
  }
</style>
