<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "book_category".
 *
 * @property int $id
 * @property string $created_at
 * @property int|null $status_id
 * @property int|null $book_id
 * @property int|null $category_id
 *
 * @property Book $book
 * @property Category $category
 */
class BookCategory extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 9;

    private static $_statuses = [
        self::STATUS_ACTIVE => 'Активно',
        self::STATUS_DELETED => 'Удалено',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_category}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['status_id', 'book_id', 'category_id'], 'integer'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'status_id' => 'Статус',
            'book_id' => 'ID Книги',
            'category_id' => 'ID Категории',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
