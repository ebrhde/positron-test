<?php

namespace console\controllers;

use common\models\Book;
use common\models\BookCategory;
use common\models\Category;
use yii\helpers\ArrayHelper;
use Yii;

class BookController extends \yii\console\Controller
{
    private $_assetUrl = '@backend/web/uploads/books';

    public function actionParse() {
        echo '=== START PARSING AT ' . date('d-m-Y H:i') . ' ==='  . PHP_EOL;

        $booksTitles = [];
        $books = Book::find()->all();

        if($books)
            $booksTitles = ArrayHelper::getColumn($books, 'title');

        $sourceUrl = \Yii::$app->setup['source_url'];

        if($sourceUrl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $sourceUrl);
            $result = curl_exec($ch);
            curl_close($ch);

            $json_data = json_decode($result);

            if($json_data && $resultsTotal = count($json_data)) {
                echo 'Books count: ' . $resultsTotal . PHP_EOL;

                if(!$newCategory = Category::find()->andWhere(['title' => 'New'])->one()) {
                    $newCategory = new Category();
                    $newCategory->status_id = Category::STATUS_ACTIVE;
                    $newCategory->title = 'New';
                    $newCategory->alias = 'new';
                    $newCategory->save();
                }

                foreach ($json_data as $key => $result) {

                    if(isset($result->title) && $result->title && array_search($result->title, $booksTitles))
                        continue;

                    $newBook = new Book();

                    if(isset($result->isbn) && $result->isbn)
                        $newBook->isbn = $result->isbn;

                    $newBook->status_id = Book::STATUS_ACTIVE;

                    if(isset($result->title) && $result->title) {
                        $newBook->title = $result->title;
                        $newBook->alias = $this->slugify($result->title);
                    }
                    if(isset($result->pageCount) && $result->pageCount)
                        $newBook->page_count = $result->pageCount;

                    if(isset($result->publishedDate) && $result->publishedDate) {
                        $publishDateString = json_encode($result->publishedDate);
                        $publishDate = explode('"',$publishDateString)[3];
                        if($publishDate)
                            $newBook->published_date = date('Y-m-d', strtotime($publishDate));
                    }

                    if(isset($result->thumbnailUrl) && $result->thumbnailUrl) {
                        $date = date('Y-m-d', time());
                        $dirName = Yii::getAlias($this->_assetUrl);


                        if (!is_dir($dirName . '/' . $date))
                            mkdir($dirName . '/' . $date, 0775, true);

                        $imageFile = $this->getImageFromUrl($result->thumbnailUrl);

                        $fileName = uniqid() . '.jpg';

                        $savefile = fopen($dirName . '/' . $date . '/' . $fileName, 'w');
                        fwrite($savefile, $imageFile);
                        fclose($savefile);

                        $newBook->thumbnail = $date . '/' . $fileName;
                    }

                    if(isset($result->shortDescription) && $result->shortDescription)
                        $newBook->announce = $result->shortDescription;
                    if(isset($result->longDescription) && $result->longDescription)
                        $newBook->description = $result->longDescription;
                    if(isset($result->status) && $result->status)
                        $newBook->ex_status = $result->status;
                    if(isset($result->authors) && $result->authors)
                        $newBook->authors = implode(',', $result->authors);

                    $relatedCategories = [];

                    if(isset($result->categories) && $result->categories) {
                        foreach ($result->categories as $category) {
                            if(!$selectedCategory = Category::find()->andWhere(['title' => $category])->one()) {
                                $selectedCategory = new Category();
                                $selectedCategory->status_id = Category::STATUS_ACTIVE;
                                $selectedCategory->title = $category;
                                $selectedCategory->alias = $this->slugify($category);
                                $selectedCategory->save();
                            }

                            $relatedCategories[] = $selectedCategory->id;
                        }
                    } else {
                        $relatedCategories[] = $newCategory->id;
                    }

                    $newBook->save();

                    foreach ($relatedCategories as $relatedCategory) {
                        $bookCategory = new BookCategory();
                        $bookCategory->status_id = BookCategory::STATUS_ACTIVE;
                        $bookCategory->book_id = $newBook->id;
                        $bookCategory->category_id = $relatedCategory;
                        $bookCategory->save();
                    }

                    if($key !== 0 && $key % 100 === 0)
                        echo 'Loaded ' . $key . '/' . $resultsTotal . ' books' . PHP_EOL;
                }

                echo '=== END PARSING AT ' . date('d-m-Y H:i') . ' ==='  . PHP_EOL;
            }
        } else
            echo 'No data source provided' . PHP_EOL;
    }

    private function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    private function getImageFromUrl($link) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}