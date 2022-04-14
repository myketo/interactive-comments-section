<template>
  <div class="comment">
    <div class="comment__top-section">
      <img :src="getImage()" :alt="getImageAlt()" class="user-avatar" />
      <p class="username">{{ data.user.username }}</p>
      <p class="created_time_ago">{{ getCreatedTimeAgo() }}</p>
    </div>

    <div class="comment__middle-section">
      <p class="message">{{ data.content }}</p>
    </div>

    <div class="comment__bottom-section">
      <comment-score :score="data.score"></comment-score>
      <comment-reply></comment-reply>
    </div>
  </div>
</template>

<script>
import CommentScore from './CommentScore';
import CommentReply from './CommentReply';

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

  components: {
    CommentScore,
    CommentReply,
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
    }
  },
}
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

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

      .created_time_ago {
        color: $grayish-blue;
      }
    }

    .comment__middle-section {
      margin: 1em 0;

      .message {
        color: $grayish-blue;
      }
    }

    .comment__bottom-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  }
</style>
