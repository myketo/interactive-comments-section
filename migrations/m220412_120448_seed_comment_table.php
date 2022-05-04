<?php

use yii\db\Migration;

/**
 * Class m220412_120448_seed_comment_table
 */
class m220412_120448_seed_comment_table extends Migration
{
    private array $challengeComments = [
        [
            'content' => "Impressive! Though it seems the drag feature could be improved. But overall it looks incredible. You've nailed the design and the responsiveness at various breakpoints works really well.",
            'score' => 12,
            'user_id' => 2,
            'created_at' => 'NOW() - INTERVAL 1 MONTH',
        ],
        [
            'content' => "Woah, your project looks awesome! How long have you been coding for? I'm still new, but think I want to dive into React as well soon. Perhaps you can give me an insight on where I can learn React? Thanks!",
            'score' => 5,
            'user_id' => 3,
            'created_at' => 'NOW() - INTERVAL 2 WEEK',
        ],
        [
            'content' => "If you're still new, I'd recommend focusing on the fundamentals of HTML, CSS, and JS before considering React. It's very tempting to jump ahead but lay a solid foundation first.",
            'score' => 4,
            'user_id' => 4,
            'parent_comment_id' => 2,
            'created_at' => 'NOW() - INTERVAL 1 WEEK',
        ],
        [
            'content' => "I couldn't agree more with this. Everything moves so fast and it always seems like everyone knows the newest library/framework. But the fundamentals are what stay constant.",
            'score' => 2,
            'user_id' => 1,
            'parent_comment_id' => 3,
            'created_at' => 'NOW() - INTERVAL 2 DAY',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertChallengeComments();
    }

    /**
     * Adding comments and replies defined in the challenge's JSON file to the `comment` table.
     *
     * @return void
     */
    private function insertChallengeComments(): void
    {
        foreach ($this->challengeComments as $challengeComment) {
            $challengeComment['created_at'] = new \yii\db\Expression($challengeComment['created_at']);

            $this->insert('comment', $challengeComment);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220412_120448_seed_comment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220412_120448_seed_comment_table cannot be reverted.\n";

        return false;
    }
    */
}
