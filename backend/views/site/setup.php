<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Настройки сайта';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="setup-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php if (Yii::$app->user->can('admin')): ?>
                <?= $form->field($model, 'books_limit')->textInput()->label('Количество книг на странице'); ?>
                <?= $form->field($model, 'feedback_email')->textInput()->label('Email для форм обратной связи'); ?>
                <?= $form->field($model, 'source_url')->textInput()->label('URL Источника данных для парсинга '); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>