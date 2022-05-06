<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $content
 * @property int|null $score
 * @property int $user_id
 * @property int|null $parent_comment_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Comment[] $comments
 * @property CommentVote[] $commentVotes
 * @property Comment $parentComment
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    public array $userVotes = [];
    public string $parentCommentAuthor = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['content', 'user_id'], 'required'],
            [['content'], 'string'],
            [['score', 'user_id', 'parent_comment_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['parent_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => __CLASS__, 'targetAttribute' => ['parent_comment_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'score' => 'Score',
            'user_id' => 'User ID',
            'parent_comment_id' => 'Parent Comment ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getReplies(): ActiveQuery
    {
        return $this->hasMany(__CLASS__, ['parent_comment_id' => 'id']);
    }

    /**
     * Gets query for [[CommentVotes]].
     *
     * @return ActiveQuery
     */
    public function getCommentVotes(): ActiveQuery
    {
        return $this->hasMany(CommentVote::class, ['comment_id' => 'id']);
    }

    /**
     * Gets query for [[ParentComment]].
     *
     * @return ActiveQuery
     */
    public function getParentComment(): ActiveQuery
    {
        return $this->hasOne(__CLASS__, ['id' => 'parent_comment_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Overwriting parent's fields method to remove user_id and add the whole User object.
     *
     * @return string[]
     */
    public function fields(): array
    {
        $fields = parent::fields();

        $this->addUserField($fields);
        $this->addParentCommentAuthor($fields);
        $this->addUserVotesField($fields);

        return $fields;
    }

    private function addParentCommentAuthor(&$fields): void
    {
        // Only adding the field when there is a parent comment.
        if (!$this->parentComment) {
            return;
        }

        // Finding the index of parent_comment_id field.
        $position = array_search('parent_comment_id', array_keys($fields), true) + 1;

        // Adding the parent_comment_author field after parent_comment_id.
        $fields = array_slice($fields, 0, $position, true)
            + ['parent_comment_author' => 'parentCommentAuthor']
            + array_slice(
                $fields,
                $position,
                count($fields) - $position,
                true
            );

        // Setting field's value.
        $this->parentCommentAuthor = $this->parentComment->user->username;
    }

    /**
     * Adding the user field to return more data about the user than just his ID.
     *
     * @param $fields
     * @return void
     */
    private function addUserField(&$fields): void
    {
        // Finding the index of user_id field.
        $position = array_search('user_id', array_keys($fields), true) + 1;

        // Replacing the user_id field with the user model.
        unset($fields['user_id']);
        $fields = array_slice($fields, 0, $position, true)
            + ['user' => 'user']
            + array_slice(
                $fields,
                $position,
                count($fields) - $position,
                true
            );
    }

    /**
     * Gets all comment votes and vote's users and assigns it to the model as a field.
     *
     * @param $fields
     * @return void
     */
    private function addUserVotesField(&$fields): void
    {
        $fields['user_votes'] = 'userVotes';

        $userVotes = [];
        foreach ($this->commentVotes as $commentVote) {
            $userVotes[$commentVote->user_id] = (int)$commentVote->status;
        }

        $this->userVotes = $userVotes;
    }
}
