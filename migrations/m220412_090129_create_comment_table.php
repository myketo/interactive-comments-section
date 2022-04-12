<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m220412_090129_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->notNull(),
            'score' => $this->integer()->defaultValue(0),
            'user_id' => $this->integer()->notNull(),
            'parent_comment_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-comment-user_id',
            'comment',
            'user_id',
        );

        $this->addForeignKey(
            'fk-comment-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-comment-parent_comment_id',
            'comment',
            'parent_comment_id',
            'comment',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-comment-user_id',
            'comment',
        );

        $this->dropForeignKey(
            'fk-comment-parent_comment_id',
            'comment',
        );

        $this->dropIndex(
            'idx-comment-user_id',
            'comment',
        );

        $this->dropTable('comment');
    }
}
