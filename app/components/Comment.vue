<template>
  <div class="comment-container" :class="{'is-response': isResponse()}">
    <div class="comment">
      <div class="comment-section comment-section__top">
        <img :src="getImage()" :alt="getImageAlt()" class="user-avatar" />
        <p class="username">{{ data.user.username }}</p>
        <span class="is-you" v-if="isCurrentUser()">you</span>
        <p class="created_time_ago">{{ getCreatedTimeAgo() }}</p>
      </div>

      <div class="comment-section comment-section__middle">
        <p class="message" v-show="!showEditCommentField">
          <span class="response-to" v-if="isResponse()">@{{ data.parent_comment_author }}</span>
          {{ data.content }}
        </p>

        <comment-textarea v-show="showEditCommentField" v-model="editedContent"></comment-textarea>
        <big-button action="edit" v-show="showEditCommentField" @editComment="editComment()"></big-button>
      </div>

      <div class="comment-section comment-section__bottom">
        <comment-score :score="data.score"></comment-score>

        <comment-button action="reply" v-if="!isCurrentUser()" @showReplyComment="toggleShowReplyField()"></comment-button>
        <div class="current-user-buttons" v-else>
          <comment-button action="delete" @showDeleteComment="toggleShowDeleteCommentModal()"></comment-button>
          <comment-button action="edit" @showEditComment="toggleShowEditCommentField()"></comment-button>
        </div>
      </div>
    </div>
  </div>

  <div class="comment-container new-reply" :class="{ 'is-response': isResponse(), 'hidden': !showReplyField }">
    <div class="comment">
      <div class="comment-section comment-section__top">
        <img :src="getImage(true)" :alt="getImageAlt(true)" class="user-avatar" />
        <comment-textarea v-show="showReplyField" v-model="newReply"></comment-textarea>
      </div>

      <big-button action="reply" @replyComment="replyToComment()"></big-button>
    </div>
  </div>

  <delete-comment-modal
    v-show="showDeleteCommentModal"
    @cancelDelete="toggleShowDeleteCommentModal()"
    @confirmDelete="deleteComment()"
  ></delete-comment-modal>
</template>

<script>
import CommentScore from './CommentScore';
import CommentButton from './CommentButton';
import BigButton from "./BigButton";
import CommentTextarea from "./CommentTextarea";
import DeleteCommentModal from "./DeleteCommentModal";

import api from "../api";

import TimeAgo from 'javascript-time-ago';
import en from 'javascript-time-ago/locale/en';
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

  emits: [
    "commentsUpdated",
  ],

  data() {
    return {
      showEditCommentField: false,
      showReplyField: false,
      showDeleteCommentModal: false,
      editedContent: this.data.content,
      newReply: '',
    }
  },

  computed: {
    currentUser() {
      return JSON.parse(localStorage.currentUser)
    }
  },

  components: {
    CommentScore,
    CommentButton,
    BigButton,
    CommentTextarea,
    DeleteCommentModal,
  },

  methods: {
    getImage(currentUser = false) {
      let imageUser = this.data.user.username
      if (currentUser) {
        imageUser = this.currentUser.username
      }

      return `../images/avatars/image-${imageUser}.webp`;
    },
    getImageAlt(currentUser = false) {
      let imageUser = this.data.user.username
      if (currentUser) {
        imageUser = this.currentUser.username
      }

      return `${imageUser}'s avatar`;
    },
    getCreatedTimeAgo() {
      return timeAgo.format(new Date(this.data.created_at))
    },
    isCurrentUser() {
      const currentUser = this.currentUser

      return currentUser.id === this.data.user.id
    },
    isResponse() {
      return this.data.parent_comment_id !== null
    },
    toggleShowEditCommentField() {
      this.showEditCommentField = !this.showEditCommentField
    },
    editComment() {
      if (this.editedContent !== this.data.content) {
        api.helpPatch(`comments/${this.data.id}`, { content: this.editedContent })

        this.data.content = this.editedContent
      }

      this.toggleShowEditCommentField()
    },
    toggleShowReplyField() {
      this.showReplyField = !this.showReplyField
    },
    replyToComment() {
      if (this.newReply !== '') {
        const data = {
          content: this.newReply,
          user_id: this.currentUser.id,
          parent_comment_id: this.data.id,
        }
        api.helpPost('comments', data).then(() => {
          this.$nextTick(() => {
            this.$emit('commentsUpdated')
          })
        })

        this.newReply = ''
      }

      this.toggleShowReplyField()
    },
    toggleShowDeleteCommentModal() {
      this.showDeleteCommentModal = !this.showDeleteCommentModal

      if (this.showDeleteCommentModal) {
        document.querySelector("body").style.overflow = "hidden"
      } else {
        document.querySelector("body").style.overflow = "initial"
      }
    },
    deleteComment() {
      api.helpDelete(`comments/${this.data.id}`).then(() => {
        this.$nextTick(() => {
          this.$emit('commentsUpdated')
        })
      })

      this.toggleShowDeleteCommentModal()
    }
  },
}
</script>

<style lang="scss">
  @import "/scss/_variables.scss";

  .comment-container {
    padding-bottom: 1em;

    &.is-response {
      border-left: 2px solid $light-gray;
      padding-left: 1em;
    }

    &.hidden {
      display: none;
    }

    &.new-reply {
      .comment {
        .comment-section {
          &.comment-section__top {
            align-items: flex-start;
          }

          .user-avatar {
            width: 2.5em;
            min-width: 2.5em;
          }
        }
      }
    }

    .comment {
      background-color: $white;
      width: 100%;
      padding: 1em;
      border-radius: 0.33em;

      .comment-section {
        .user-avatar {
          width: 2em;
          min-width: 2em;
          height: fit-content;
        }

        &.comment-section__top {
          display: flex;
          align-items: center;
          gap: 1em;

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

        &.comment-section__middle {
          margin: 1em 0;

          .message {
            color: $grayish-blue;
            word-break: break-word;

            .response-to {
              color: $moderate-blue;
              font-weight: $font-weight-medium;
            }
          }
        }

        &.comment-section__bottom {
          display: flex;
          justify-content: space-between;
          align-items: center;

          .current-user-buttons {
            display: flex;
          }
        }
      }
    }
  }
</style>
