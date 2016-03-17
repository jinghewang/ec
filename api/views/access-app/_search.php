<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\AccessAppSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-app-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options'=>['class'=>'form-inline rb-form-search'],
    ]); ?>

    <?= $form->field($model, 'appkey')->label('关键字',['title'=>'搜索包括应用标识和应用名称'])->textInput(['placeholder'=>'应用标识 | 应用名称']) ?>

    <?php //echo $form->field($model, 'appname') ?>

    <?php //echo $form->field($model, 'client_id') ?>

    <?php //echo $form->field($model, 'client_secret') ?>

    <?php //echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <div class="form-group">
        <?= Html::button('Search', ['class' => 'btn btn-primary rb-search-btn']) ?>
        <?php //echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
