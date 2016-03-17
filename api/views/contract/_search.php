<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'contr_id') ?>

    <?= $form->field($model, 'contr_no') ?>

    <?= $form->field($model, 'vercode') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'is_lock') ?>

    <?php // echo $form->field($model, 'lock_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'audit_status') ?>

    <?php // echo $form->field($model, 'is_submit') ?>

    <?php // echo $form->field($model, 'sub_time') ?>

    <?php // echo $form->field($model, 'sign_time') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'num') ?>

    <?php // echo $form->field($model, 'transactor') ?>

    <?php // echo $form->field($model, 'oldcontr') ?>

    <?php // echo $form->field($model, 'orgid') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'extra_data') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
