<?php

use yii\helpers\Html;

$this->title = $book->title;
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['catalog/index', 'alias' => $category->alias]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::img($book->getPhoto(150, 150, 'resize')); ?>
    <?php if(isset($book->authors) && $book->authors): ?>
        <p>Авторы: <?= $book->authors; ?></p>
    <?php endif; ?>
    <?php if(isset($book->page_count) && $book->page_count): ?>
        <p>Количество страниц: <?= $book->page_count; ?></p>
    <?php endif; ?>
    <?php if(isset($book->isbn) && $book->isbn): ?>
        <p>Артикул (ISBN): <?= $book->isbn; ?></p>
    <?php endif; ?>
    <?php if(isset($book->description) && $book->description): ?>
        <h3>Описание:</h3>
        <p><?= $book->description; ?></p>
    <?php elseif (isset($book->announce) && $book->announce):?>
        <h3>Краткое описание:</h3>
        <p><?= $book->announce; ?></p>
    <?php endif; ?>
</div>
