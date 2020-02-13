<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%datums}}`.
 */
class m200211_194621_create_datums_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%datums}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->unsigned()->notNull(),
            'code' => $this->string()->notNull(),
        ]);

        $this->createIndex('{{%idx-datums-code}}', '{{%datums}}', 'code', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%datums}}');
    }
}
