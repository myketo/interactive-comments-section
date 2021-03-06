<template>
  <div class="comments-section">
    <comment
      v-for="comment in comments"
      :key="comment.id"
      :data="comment"
      @commentsUpdated="loadComments">
    </comment>

    <div class="comment-container new-comment">
      <div class="comment">
        <div class="comment-content">
          <div class="comment-section comment-section__top">
            <img
                :src="getCurrentUserImage()"
                :alt="getCurrentUserImageAlt()"
                class="user-avatar"
                v-if="!isMobile"
            />

            <comment-textarea v-model="newComment" placeholder="Add a comment..."></comment-textarea>
          </div>

          <div class="comment-section comment-section__bottom">
            <img
                :src="getCurrentUserImage()"
                :alt="getCurrentUserImageAlt()"
                class="user-avatar"
                v-if="isMobile"
            />

            <big-button action="send" @sendComment="createComment()"></big-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../api";
import Comment from "./Comment";
import CommentTextarea from "./CommentTextarea";
import BigButton from "./BigButton";
import mixinDetectMobile from "../mixins/detectMobile";

export default {
  name: "CommentsSection",

  components: {
    Comment,
    CommentTextarea,
    BigButton,
  },

  data() {
    return {
      comments: [],
      newComment: '',
    }
  },

  computed: {
    currentUser() {
      return JSON.parse(localStorage.currentUser)
    }
  },

  mixins: [
    mixinDetectMobile,
  ],

  methods: {
    async loadComments() {
      this.comments = await api.helpGet('comments')

      this.setLastResponses()
    },
    getCurrentUserImage() {
      return `../images/avatars/image-${this.currentUser.username}.webp`;
    },
    getCurrentUserImageAlt() {
      return `${this.currentUser.username}'s avatar`;
    },
    createComment() {
      if (this.newComment === '') {
        return
      }

      const data = {
        content: this.newComment,
        user_id: this.currentUser.id,
      }
      api.helpPost('comments', data).then(() => {
        this.$nextTick(() => {
          this.loadComments()
        })
      })

      this.newComment = ''
    },
    setLastResponses() {
      let filteredComments = this.comments.filter((comment, index, comments) => {
        // Check if comment is a response.
        if (comment.parent_comment_id === null) return false

        // Check if there are comments after.
        if (comments[index + 1] === undefined) return true

        // Choose only latest replies.
        return comments[index + 1].parent_comment_id === null
      })

      this.comments.forEach((comment, index) => {
        if (filteredComments.indexOf(comment) !== -1) {
          // Setting is_last_response value for selected replies.
          this.comments[index].is_last_response = true
        }
      })
    }
  },

  beforeMount() {
    this.loadComments()
  }
}
</script>

<style lang="scss" scoped>
  @import "/scss/_variables.scss";

  .comments-section {
    background-color: $very-light-gray;
    padding: 2em 1em;

    @media (min-width: $min-desktop) {
      padding: 3.5em 21.75em;
    }

    .new-comment {
      display: flex;
      width: 100%;

      .comment {
        justify-content: space-between;

        .comment-content {
          @media (min-width: $min-desktop) {
            display: flex;
            align-items: flex-start;
          }

          .comment-section {
            .user-avatar {
              width: 2.5em;
              min-width: 2.5em;
              height: fit-content;
            }

            &.comment-section__top {
              width: 100%;
              display: flex;
              gap: 1em;
              margin-right: 1em;

              @media (min-width: $min-desktop) {
                align-items: flex-start;
              }

              .comment-textarea {
                width: 100%;
              }
            }

            &.comment-section__bottom {
              margin-top: 1em;

              @media (min-width: $min-desktop) {
                margin-top: 0;
              }

              .big-button-container {
                @media (min-width: $min-desktop) {
                  margin-top: 0;
                }
              }
            }
          }
        }
      }
    }
  }
</style>