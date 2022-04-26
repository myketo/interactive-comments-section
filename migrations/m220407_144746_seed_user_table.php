<?php

use yii\db\Migration;

/**
 * Class m220407_144746_seed_user_table
 */
class m220407_144746_seed_user_table extends Migration
{
    private array $challengeUsers = [
        'juliusomo',
        'amyrobson',
        'maxblagun',
        'ramsesmiron',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertChallengeUsers();
    }

    /**
     * Adding users defined in the challenge's JSON file to the `user` table.
     *
     * @return void
     * @throws \yii\base\Exception
     */
    private function insertChallengeUsers(): void
    {
        foreach ($this->challengeUsers as $challengeUser) {
            $this->insert('user', [
                'username' => $challengeUser,
                'password_hash' => password_hash('kanapka', PASSWORD_DEFAULT),
                'auth_key' => \Yii::$app->security->generateRandomString(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220407_144746_seed_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220407_144746_seed_user_table cannot be reverted.\n";

        return false;
    }
    */
}
