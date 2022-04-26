<?php

namespace app\controllers;

class CommentVoteController extends \yii\rest\ActiveController
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
