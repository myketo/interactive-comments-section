<?php

namespace app\bootstrap;

use app\models\UserAuth;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Logging in a default user for presentational purposes.
 */
class DefaultUserBootstrap implements BootstrapInterface
{
    private string $defaultUsername = 'juliusomo';

    public function bootstrap($app): void
    {
        $identity = UserAuth::findOne(['username' => $this->defaultUsername]);

        if (Yii::$app->user->identity || !$identity) {
            return;
        }

        Yii::$app->user->login($identity, 3600 * 24 * 30);
    }
}
