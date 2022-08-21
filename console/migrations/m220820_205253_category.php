<?php

use yii\db\Migration;

/**
 * Class m220820_205253_category
 */
class m220820_205253_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'status_id' => $this->tinyInteger(1),
            'title' => $this->string(255),
            'alias' => $this->string(255)
        ], $tableOptions);

        $this->createTable('{{%book_category}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'status_id' => $this->tinyInteger(1),
            'book_id' => $this->integer(11),
            'category_id' => $this->integer(11)
        ], $tableOptions);

        $this->addForeignKey(
            'FK_bc_book',
            '{{%book_category}}',
            'book_id',
            '{{%book}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'FK_bc_category',
            '{{%book_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_bc_category', '{{%book_category}}');
        $this->dropForeignKey('FK_bc_book', '{{%book_category}}');
        $this->dropTable('{{%book_category}}');
        $this->dropTable('{{%category}}');
    }
}
