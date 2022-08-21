<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Book;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Book */
/* @var $form yii\widgets\ActiveForm */

$categoriesList = [];
$categories = Category::find()
    ->andWhere(['status_id' => Category::STATUS_ACTIVE])
    ->orderBy(['title' => SORT_ASC])
    ->all();
if ($categories) {
    $categoriesList = ArrayHelper::map($categories, 'id', function ($model) {
        return $model->title;
    });
}

if (!$model->status_id) $model->status_id = Book::STATUS_ACTIVE;

if (!$model->removeImage)
    $model->removeImage = Book::NO;
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->radioList(Book::getStatuses()); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'page_count')->textInput() ?>

    <?= $form->field($model, 'published_date')->textInput() ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'file')->fileInput(); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'removeImage')->radioList(Book::getOptions()); ?>
        </div>
    </div>

    <?= $form->field($model, 'announce')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'authors')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ex_status')->textInput(['maxlength' => true]) ?>

    <strong>Категории:</strong>
    <?= $form->field($model, 'categoriesBook')->widget(Select2::class, [
        'data' => $categoriesList,
        'options' => ['multiple' => true],
    ])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
