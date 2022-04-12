<?php

namespace app\controllers;

use app\models\Comment;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\rest\IndexAction;

class CommentController extends ActiveController
{
    public $modelClass = Comment::class;

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class' => IndexAction::class,
            'modelClass' => $this->modelClass,
            'prepareDataProvider' => fn() => new ActiveDataProvider([
                'query' => $this->modelClass::find(),
                'pagination' => false,
            ]),
        ];

        return $actions;
    }
}
