<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'isbn',
            'created_at',
//            'updated_at',
            'status',
            //'alias',
            'title',
            'picture:raw',
            'page_count',
            'published_date',
            //'announce:ntext',
            //'description:ntext',
            'authors',
            'ex_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
