<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\AccessTokenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-token-search">

    <?php $form = ActiveForm::begin([
        'id' => 'app-search-form',
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline']
    ]); ?>

    <?= $form->field($model, 'tokenid')->label('关键字')->textInput(['placeholder'=>'搜索关键字']) ?>

    <?php //echo $form->field($model, 'clientid') ?>

    <?php //echo $form->field($model, 'appkey') ?>

    <?php //echo $form->field($model, 'orgid') ?>

    <?php //echo $form->field($model, 'uid') ?>

    <?php // echo $form->field($model, 'validity') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('清空', ['class' => 'btn btn-default']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
