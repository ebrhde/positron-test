<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Категории книг:</h1>
            <ul class="list-group mt-5">
                <?php foreach ($categories as $category): ?>
                <li class="list-group-item">
                    <?=Html::a($category->title, ['catalog/index', 'alias' => $category->alias], ['class' => ''])?>
                </li>
                <?php endforeach; ?>
            </ul>


        </div>
    </div>
</div>
