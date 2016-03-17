<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CountrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?php //= $form->field($model, 'name') ?>

    <?php //echo $form->field($model, 'population') ?>

    <?php //echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?php //= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php //= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>

        <?= Html::a('Search', null, ['class' => 'btn btn-info rb-search-btn']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
