<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string|null $isbn
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $status_id
 * @property string|null $alias
 * @property string|null $title
 * @property int|null $page_count
 * @property string|null $published_date
 * @property string|null $thumbnail
 * @property string|null $announce
 * @property string|null $description
 * @property string|null $authors
 * @property string|null $ex_status
 */
class Book extends \yii\db\ActiveRecord
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
        return '{{%book}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            [['created_at', 'updated_at', 'published_date'], 'safe'],
            [['status_id', 'page_count'], 'integer'],
            [['announce', 'description', 'alias', 'isbn'], 'string'],
            [['title', 'thumbnail', 'authors', 'ex_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isbn' => 'Isbn',
            'created_at' => 'Добавлена',
            'updated_at' => 'Изменена',
            'status_id' => 'Статус (внутренний)',
            'alias' => 'Псевдоним',
            'title' => 'Название',
            'page_сount' => 'Количество страниц',
            'published_date' => 'Дата публикации',
            'thumbnail' => 'Изображение обложки',
            'announce' => 'Аннотация',
            'description' => 'Подробное описание',
            'authors' => 'Авторы',
            'ex_status' => 'Статус (внешний)',
        ];
    }
}
