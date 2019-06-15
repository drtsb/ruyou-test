<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190614_105209_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'email_confirm_token' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
        ], $tableOptions);
 
        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-email', '{{%user}}', 'email');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');

        $this->insert( '{{%user}}', [
            'created_at' => time(),
            'updated_at' => time(),
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'admin@gmail.com',
        ]);

        $this->insert( '{{%user}}', [
            'created_at' => time(),
            'updated_at' => time(),
            'username' => 'user',
            'password_hash' => Yii::$app->security->generatePasswordHash('user'),
            'email' => 'user@gmail.com',
        ]);

        $this->insert( '{{%user}}', [
            'created_at' => time(),
            'updated_at' => time(),
            'username' => 'demo',
            'password_hash' => Yii::$app->security->generatePasswordHash('demo'),
            'email' => 'demo@gmail.com',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
