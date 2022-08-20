<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Административная панель</h1>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-info">
                    <div class="panel-heading"><h3>Контент</h3></div>
                    <div class="panel-body">
                        <?php if (Yii::$app->user->can('admin')): ?>
                            <h4>Контент</h4>
                            <ul>
                                <li><?= Html::a('Категории', ['category/index']); ?></li>
                                <li><?= Html::a('Книги', ['book/index']); ?></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-danger">
                    <div class="panel-heading"><h3>Система</h3></div>
                    <div class="panel-body">
                        <?php if (Yii::$app->user->can('admin')): ?>
                            <h4>Обратная связь</h4>
                            <ul>
                                <li><?= Html::a('Формы обратной связи', ['feedback/index', 'type' => 'manager']); ?></li>
                            </ul>
                            <h4>Настройки</h4>
                            <ul>
                                <li><?= Html::a('Настройки', ['site/setup', 'type' => 'manager']); ?></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

