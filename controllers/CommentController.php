<?php

namespace app\controllers;

use app\models\Comment;
use app\models\CommentVote;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\rest\IndexAction;

class CommentController extends ActiveController
{
    /**
     * @var Comment $modelClass
     */
    public $modelClass = Comment::class;

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['index']);

        return $actions;
    }

    public function actionIndex(): array
    {
        $comments = $this->modelClass::find()
            ->where(['IS', 'parent_comment_id', new yii\db\Expression('NULL')])
            ->all();

        $response = [];
        $this->addReplies($comments, $response);

        return $response;
    }

    private function addReplies($comments, &$response): void
    {
        foreach ($comments as $comment) {
            $response[] = $comment;

            if ($comment->replies) {
                $this->addReplies($comment->replies, $response);
            }
        }
    }

    /**
     * Either incrementing or decrementing comment's score value.
     *
     * @param $commentId
     * @return bool|Comment
     * @throws InvalidConfigException
     */
    public function actionUpdateScore($commentId): bool|Comment
    {
        $status = Yii::$app->getRequest()->getBodyParams()['status'] ?? null;
        if ($status === null) {
            exit('"status" field is required.');
        }

        if (!in_array($status, [0, 1, -1], true)) {
            exit('Value of "status" field is invalid.');
        }

        $commentModel = $this->modelClass::findOne($commentId);
        if (!$commentModel) {
            exit("Comment with id: $commentId does not exist.");
        }

        $commentUser = [
            'comment_id' => $commentId,
            'user_id' => Yii::$app->user->identity->id,
        ];

        $commentVoteModel = CommentVote::findOne($commentUser);
        if (!$commentVoteModel) {
            $commentVoteModel = new CommentVote();
            $commentVoteModel->load($commentUser, '');
        }

        $prevStatus = (int)($commentVoteModel->status ?? 0);

        if ($prevStatus === $status) {
            return $commentModel;
        }

        $commentVoteModel->status = (string)$status;
        if ($status === 0 && $prevStatus !== 0) {
            $status = -$prevStatus;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$commentModel->updateCounters(['score' => $status])) {
                $errors = array_values($commentModel->getErrors())[0][0];
                throw new Exception($errors);
            }

            if (!$commentVoteModel->save()) {
                $errors = array_values($commentVoteModel->getErrors())[0][0];
                throw new Exception($errors);
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            echo "({$e->getLine()}) {$e->getMessage()}\n";
            exit('Error has occurred while trying to update the score.');
        }

        return $commentModel;
    }
}
