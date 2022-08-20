<?php

use yii\db\Migration;

/**
 * Class m220820_134042_book
 */
class m220820_134042_book extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'isbn' => $this->string(255),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'status_id' => $this->tinyInteger(1),
            'alias' => $this->string(255),
            'title' => $this->string(255),
            'page_count' => $this->integer(11),
            'published_date' => $this->dateTime(),
            'thumbnail' => $this->string(255),
            'announce' => $this->text(),
            'description' => $this->text(),
            'authors' => $this->string(255),
            'ex_status' => $this->string(255)
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%book}}');
    }
}
