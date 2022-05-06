<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment_vote`.
 */
class m220415_093930_create_comment_vote_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comment_vote', [
            'id' => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => 'ENUM("-1", "0", "1")',
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-comment_vote-user_id',
            'comment_vote',
            'user_id',
        );

        $this->createIndex(
            'idx-comment_vote-comment_id',
            'comment_vote',
            'comment_id',
        );

        $this->addForeignKey(
            'fk-comment_vote-user_id',
            'comment_vote',
            'user_id',
            'user',
            'id',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-comment_vote-comment_id',
            'comment_vote',
            'comment_id',
            'comment',
            'id',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-comment_vote-user_id',
            'comment_vote',
        );

        $this->dropForeignKey(
            'fk-comment_vote-comment_id',
            'comment_vote',
        );

        $this->dropIndex(
            'idx-comment_vote-user_id',
            'comment_vote',
        );

        $this->dropIndex(
            'idx-comment_vote-comment_id',
            'comment_vote',
        );

        $this->dropTable('comment_vote');
    }
}
