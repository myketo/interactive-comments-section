<?php

namespace app\controllers;

use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use app\models\User;
use yii\rest\IndexAction;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions(): array
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
