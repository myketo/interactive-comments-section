<?php

namespace app\models;

use yii\web\IdentityInterface;

/**
 * Class that takes care of user's identity and authentication
 * (used in User component's identityClass).
 *
 * @property-read null|string $authKey
 */
class UserAuth extends User implements IdentityInterface
{
    /**
     * @param $id
     * @return UserAuth|IdentityInterface|null
     */
    public static function findIdentity($id): UserAuth|IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * @param $token
     * @param $type
     * @return UserAuth|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): UserAuth|IdentityInterface|null
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }
}
