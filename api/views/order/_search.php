<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'oid') ?>

    <?= $form->field($model, 'appkey') ?>

    <?= $form->field($model, 'orgid') ?>

    <?= $form->field($model, 'createuserid') ?>

    <?= $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'out_trade_no') ?>

    <?php // echo $form->field($model, 'paysum') ?>

    <?php // echo $form->field($model, 'paynum') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'post1') ?>

    <?php // echo $form->field($model, 'post2') ?>

    <?php // echo $form->field($model, 'posttime') ?>

    <?php // echo $form->field($model, 'result') ?>

    <?php // echo $form->field($model, 'resulttime') ?>

    <?php // echo $form->field($model, 'callback') ?>

    <?php // echo $form->field($model, 'callbacktime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
