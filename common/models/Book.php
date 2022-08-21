<?php

namespace common\models;

use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\imagine\Image;

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
    public $file = null;
    public $removeImage = null;
    public $categoriesBook = null;

    private $_assetsUrl = '/uploads/books/cache';
    private $_assetsOrigPath = '@backend/web/uploads/books';
    private $_assetsPath = '@webroot/uploads/books/cache';


    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 9;

    private static $_statuses = [
        self::STATUS_ACTIVE => 'Активно',
        self::STATUS_DELETED => 'Удалено',
    ];

    const YES = 1;
    const NO = 9;

    private static $_options = [
        self::YES => 'Да',
        self::NO => 'Нет',
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
            [['created_at', 'updated_at', 'published_date', 'categoriesBook'], 'safe'],
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
            'status_id' => 'Статус (внут.)',
            'alias' => 'Псевдоним',
            'title' => 'Название',
            'page_count' => 'Кол-во страниц',
            'published_date' => 'Дата публикации',
            'thumbnail' => 'Обложка',
            'announce' => 'Аннотация',
            'description' => 'Подр. описание',
            'authors' => 'Авторы',
            'ex_status' => 'Статус (внеш.)',
        ];
    }

    public function loadDependencies()
    {
        $this->categoriesBook = ArrayHelper::getColumn($this->categories, 'id');
    }

    public function getPathPicture()
    {
        if ($this->thumbnail)
            return Yii::getAlias('@backend/web/uploads/books/') . $this->thumbnail;
    }

    public function getPicture()
    {
        if (!empty($this->thumbnail)) {
            $src = $this->pathPicture;
            return '<div style="width: 90px; height: 90px">' . Html::img($src, ['style' => 'max-width:100%; max-height:100%;']) . '</div>';
        }
        return 'Не найдено';
    }

    public static function getStatuses()
    {
        return self::$_statuses;
    }

    public function getStatus($id = 0)
    {
        if (!$id) $id = $this->status_id;
        return ((!empty(self::$_statuses[$id])) ? self::$_statuses[$id] : Yii::t('new', 'No Specify'));
    }

    public function upload()
    {
        if (!$this->file) return false;

        $date = date('Y-m-d', time());
        $dirName = Yii::getAlias($this->_assetsPath);
        $fileName = uniqid() . '.' . $this->file->getExtension();

        if (!is_dir($dirName . '/' . $date)) mkdir($dirName . '/' . $date, 0775, true);
        $this->file->saveAs($dirName . '/' . $date . '/' . $fileName);

        $this->thumbnail = $date . '/' . $fileName;
        $this->file = false;
        return $this->thumbnail;
    }

    public function getBookCategories()
    {
        return $this->hasMany(BookCategory::class, ['book_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->via('bookCategories');
    }

    public static function getOptions()
    {
        return self::$_options;
    }

    public function getPhoto($width = 0, $height = 0, $method = 'thumb')
    {
        $origDir = Yii::getAlias($this->_assetsOrigPath);
        $pathDir = Yii::getAlias($this->_assetsPath);

        if (!$this->thumbnail || !is_file($origDir . '/' . $this->thumbnail))
            $this->thumbnail = 'book-default.jpg';

        $pathImage = $origDir . '/' . $this->thumbnail;

        $salt = '';
        if (is_file($pathImage))
            $salt = sha1_file($pathImage);

        if (!is_dir($pathDir))
            mkdir($pathDir, 0775, true);

        if ($width || $height) {
            if ($method == 'resize') {
                $imageCache = $pathDir . '/r-' . $salt . '-new-' . $width . 'x' . $height . '-' . $this->id . '.jpg';

                if (!is_file($imageCache)) {

                    $box = new Box($width, $height);

                    if (is_file($pathImage))
                        Image::getImagine()->open($pathImage)->resize($box)->save($imageCache, ['quality' => 90]);
                }

                if (is_file($imageCache))
                    return $this->_assetsUrl . '/r-' . $salt . '-new-' . $width . 'x' . $height . '-' . $this->id . '.jpg';

            } elseif ($method == 'thumb') {
                $imageCache = $pathDir . '/t-' . $salt . '-new-' . $width . 'x' . $height . '-' . $this->id . '.jpg';

                if (!is_file($imageCache)) {
                    $newW = $width;
                    $newH = $height;

                    if (is_file($pathImage)) {
                        $temp = Image::getImagine()->load(file_get_contents($pathImage));
                        $size = $temp->getSize();

                        if (($size->getWidth() / $size->getHeight()) > ($width / $height))
                            $newW = $newH * ($size->getWidth() / $size->getHeight());
                        else
                            $newH = ($size->getHeight() / $size->getWidth()) * $newW;

                        Image::resize($pathImage, $newW, $newH, true, true)
                            ->thumbnail(new Box($width, $height), ManipulatorInterface::THUMBNAIL_OUTBOUND)
                            ->save($imageCache, ['jpeg_quality' => 90]);
                    }
                }

                if (is_file($imageCache))
                    return $this->_assetsUrl . '/t-' . $salt . '-new-' . $width . 'x' . $height . '-' . $this->id . '.jpg';
            }
        } else {
            $imageCache = $pathDir . '/o-' . $salt . '-new-' . $this->id . '.jpg';
            Image::getImagine()->open($pathImage)->save($imageCache, ['quality' => 90]);

            if (is_file($imageCache))
                return $this->_assetsUrl . '/o-' . $salt . '-new-' . $this->id . '.jpg';
        }

        return null;
    }
}
