<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\OrganizationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organization-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'orgid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'parentid') ?>

    <?= $form->field($model, 'lft') ?>

    <?= $form->field($model, 'rgt') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'enname') ?>

    <?php // echo $form->field($model, 'vercode') ?>

    <?php // echo $form->field($model, 'seal') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'teltext') ?>

    <?php // echo $form->field($model, 'createuserid') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'extra_data') ?>

    <?php // echo $form->field($model, 'isdelete') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
