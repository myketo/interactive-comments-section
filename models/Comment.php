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
 * @property Comment $parentComment
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
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
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(__CLASS__, ['parent_comment_id' => 'id']);
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
}
