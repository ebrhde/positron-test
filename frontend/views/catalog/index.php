<?php
use yii\widgets\ListView;
use yii\helpers\Html;


$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'viewParams' => [
            'categoryAlias' => $category->alias,
                ],
        'itemView' => '_book_preview',
        'options' => [
            'tag' => 'div',
            'class' => 'row',
        ],
        'itemOptions' => [
            'tag' => 'div',
            'class' => 'col-lg-2 col-sm-3 mb-3 text-center',
        ],
        'layout' => "{pager}\n{items}\n{pager}",
    ]); ?>
</div>
