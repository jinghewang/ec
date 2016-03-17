<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-sign-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sign_id') ?>

    <?= $form->field($model, 'contr_id') ?>

    <?= $form->field($model, 'sign_name') ?>

    <?= $form->field($model, 'sign_userid') ?>

    <?= $form->field($model, 'sign_time') ?>

    <?php // echo $form->field($model, 'sign_data') ?>

    <?php // echo $form->field($model, 'sign_sign') ?>

    <?php // echo $form->field($model, 'sign_file') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'code') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'openid') ?>

    <?php // echo $form->field($model, 'bindtime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
