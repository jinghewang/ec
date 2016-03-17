<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractVersionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-version-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'vercode') ?>

    <?= $form->field($model, 'pcode') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'fileid') ?>

    <?= $form->field($model, 'orgid') ?>

    <?php // echo $form->field($model, 'extra_data') ?>

    <?php // echo $form->field($model, 'createuser') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
