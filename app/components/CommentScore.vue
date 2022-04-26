<template>
  <div class="comment-score">
    <button class="score-btn" :class="{ active: isActive(STATUS_INCREMENT) }" @click="updateScore(STATUS_INCREMENT)">
      <svg xmlns="http://www.w3.org/2000/svg" class="score-btn-icon icon-increment">
        <path
          d="M6.33 10.896c.137 0 .255-.05.354-.149.1-.1.149-.217.149-.354V7.004h3.315c.136 0 .254-.05.354-.149.099-.1.148-.217.148-.354V5.272a.483.483 0 0 0-.148-.354.483.483 0 0 0-.354-.149H6.833V1.4a.483.483 0 0 0-.149-.354.483.483 0 0 0-.354-.149H4.915a.483.483 0 0 0-.354.149c-.1.1-.149.217-.149.354v3.37H1.08a.483.483 0 0 0-.354.15c-.1.099-.149.217-.149.353v1.23c0 .136.05.254.149.353.1.1.217.149.354.149h3.333v3.39c0 .136.05.254.15.353.098.1.216.149.353.149H6.33Z"
        />
      </svg>
    </button>

    <span class="current-score">{{ currScore }}</span>

    <button class="score-btn score-decrement" :class="{ active: isActive(STATUS_DECREMENT) }"  @click="updateScore(STATUS_DECREMENT)">
      <svg xmlns="http://www.w3.org/2000/svg" class="score-btn-icon icon-decrement">
        <path
          d="M9.256 2.66c.204 0 .38-.056.53-.167.148-.11.222-.243.222-.396V.722c0-.152-.074-.284-.223-.395a.859.859 0 0 0-.53-.167H.76a.859.859 0 0 0-.53.167C.083.437.009.57.009.722v1.375c0 .153.074.285.223.396a.859.859 0 0 0 .53.167h8.495Z"
        />
      </svg>
    </button>
  </div>
</template>

<script>
import api from "../api";

export default {
  name: "CommentScore",

  props: {
    score: {
      type: Number,
      default: 0,
    },
  },

  data() {
    return {
      currScore: Number,
      currStatus: Number,
      prevStatus: Number,
    }
  },

  methods: {
    updateScore(newStatus) {
      this.prevStatus = this.currStatus
      this.currStatus = newStatus

      if (newStatus !== this.STATUS_DEFAULT) {
        if (this.prevStatus === this.STATUS_DEFAULT) {
          this.currScore += newStatus

          return this.updateScoreInDb(newStatus)
        } else {
          const changedVote = this.prevStatus === -newStatus

          if (changedVote) {
            this.currStatus = this.prevStatus
          }
          this.updateScore(this.STATUS_DEFAULT)

          if (changedVote) {
            this.updateScore(newStatus)
          }
        }
      } else {
        this.currScore += -this.prevStatus

        return this.updateScoreInDb(this.STATUS_DEFAULT)
      }
    },

    async updateScoreInDb(status) {
      const commentId = this.$parent.data.id
      return await api.helpPatch(
          `comments/${commentId}/update-score`,
          { status: status }
      )
    },

    isActive(status) {
      return status === this.currStatus
    }
  },

  created() {
    // Defining constants.
    this.STATUS_DEFAULT = 0;
    this.STATUS_INCREMENT = 1;
    this.STATUS_DECREMENT = -1;

    this.currScore = this.score

    const currentUserId = JSON.parse(localStorage.currentUser)['id'];
    this.currStatus = this.$parent.data.user_votes[currentUserId] ?? this.STATUS_DEFAULT
    this.prevStatus = this.STATUS_DEFAULT
  }
}
</script>

<style lang="scss" scoped>
@import '/scss/_variables.scss';

.comment-score {
  background-color: $very-light-gray;
  width: fit-content;
  padding: 0.5em;
  border-radius: 0.75em;
  display: flex;
  align-items: center;
  gap: 0.75em;

  .score-btn {
    border: none;
    background-color: inherit;

    .score-btn-icon {
      fill: #C5C6EF;
      transition: fill 0.2s ease-in-out;

      &.icon-increment {
        width: 12px;
        height: 12px;
      }

      &.icon-decrement {
        width: 12px;
        height: 4px;
      }
    }

    &:hover,
    &.active {
      .score-btn-icon {
        fill: $moderate-blue;
      }
    }
  }

  .current-score {
    color: $moderate-blue;
    font-weight: $font-weight-medium;
    width: 1em;
  }
}
</style>
