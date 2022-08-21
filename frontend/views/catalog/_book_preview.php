<?php
    use yii\helpers\Html;
?>

<div class="card">
    <?= Html::a(Html::img($model->getPhoto(90, 90, 'resize')),
        ['/catalog/view', 'alias' => $model->alias]);
    ?>
    <div class="card-body">
        <h5 class="card-title"><?= $model->title; ?></h5>
        <?php if(isset($model->isbn) && $model->isbn):?>
        <p class="card-text">ISBN: <?= $model->isbn ?></p>
        <?php endif;?>
        <?= Html::a('Подробнее', ['/catalog/view', 'categoryAlias' => $categoryAlias, 'alias' => $model->alias]); ?>
    </div>
</div>
