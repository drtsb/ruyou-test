<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file_role}}`.
 */
class m190614_142558_create_file_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file_role}}', [
            'file_id' => $this->integer(),
            'role' => $this->string(64),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file_role}}');
    }
}
