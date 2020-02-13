<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m200211_195017_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'datum_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
        ]);

        $this->createIndex('{{%idx-files-datum_id}}', '{{%files}}', 'datum_id');

        $this->addForeignKey('{{%fk-files-datum_id}}', '{{%files}}', 'datum_id', '{{%datums}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
