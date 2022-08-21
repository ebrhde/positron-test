<?php

namespace frontend\controllers;

use common\models\Category;
use yii\data\ActiveDataProvider;
use common\models\Book;
use yii\web\NotFoundHttpException;

class CatalogController extends \yii\web\Controller
{
    public function actionIndex($alias)
    {
        if(!$alias || !$category= Category::find()->andWhere(['status_id' => Category::STATUS_ACTIVE, 'alias' => $alias])->one()) {
            throw new NotFoundHttpException('Категория не найдена');
        };

        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => \Yii::$app->setup['books_limit']
            ],
            'query' => Book::find()->alias('b')
                ->joinWith('bookCategories bc')
                ->andWhere(['b.status_id' => Book::STATUS_ACTIVE, 'bc.category_id' => $category->id])
                ->orderBy(['published_date' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($categoryAlias, $alias)
    {
        $category = Category::find()->andWhere(['alias' => $categoryAlias, 'status_id' => Category::STATUS_ACTIVE])->one();

        if (!$alias || !$book = Book::find()->andWhere(['status_id' => Book::STATUS_ACTIVE, 'alias' => $alias])->one())
            throw new NotFoundHttpException('Книга не найдена');

        return $this->render('view', [
            'category' => $category,
            'book' => $book,
        ]);
    }
}