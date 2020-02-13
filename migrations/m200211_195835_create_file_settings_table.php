<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file_settings}}`.
 */
class m200211_195835_create_file_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file_settings}}', [
            'id' => $this->primaryKey(),
            'file_id' => $this->integer()->notNull(),

            'firstDataIndex' => $this->integer()->notNull(),
            'secondDataIndex' => $this->integer()->notNull(),
            'labelRowIndex' => $this->integer()->notNull(),
            'graphName' => $this->string()->notNull(),

            'balance' => $this->float()->null(),
            'skipTop' => $this->integer()->null(),
            'skipDown' => $this->integer()->null(),
            'maxElement' => $this->integer()->null(),
        ]);

        $this->createIndex('{{%idx-file_settings-file_id}}', '{{%file_settings}}', 'file_id');

        $this->addForeignKey('{{%fk-file_settings-file_id}}', '{{%file_settings}}', 'file_id', '{{%files}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file_settings}}');
    }
}
