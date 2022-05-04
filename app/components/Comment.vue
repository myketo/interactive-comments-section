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
        <p class="message">
          <span class="response-to" v-if="isResponse()">@{{ data.parent_comment_author }}</span>
          {{ data.content }}
        </p>
      </div>

      <div class="comment__bottom-section">
        <comment-score :score="data.score"></comment-score>

        <comment-button action="reply" v-if="!isCurrentUser()"></comment-button>
        <div class="current-user-buttons" v-else>
          <comment-button action="delete"></comment-button>
          <comment-button action="edit"></comment-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CommentScore from './CommentScore';
import CommentButton from './CommentButton';

import TimeAgo from 'javascript-time-ago'
import en from 'javascript-time-ago/locale/en.json'
TimeAgo.addDefaultLocale(en)
const timeAgo = new TimeAgo('en-US')

export default {
  name: "Comment",

  props: {
    data: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      parentCommentUsername: String,
    }
  },

  components: {
    CommentScore,
    CommentButton,
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
    }
  },
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
          .response-to {
            color: $moderate-blue;
            font-weight: $font-weight-medium;
          }

          color: $grayish-blue;
        }
      }

      .comment__bottom-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
    }
  }
</style>
