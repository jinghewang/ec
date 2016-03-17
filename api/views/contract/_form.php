<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\Contract */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contr_id')->textInput() ?>

    <?= $form->field($model, 'contr_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vercode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_lock')->textInput() ?>

    <?= $form->field($model, 'lock_time')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'audit_status')->textInput() ?>

    <?= $form->field($model, 'is_submit')->textInput() ?>

    <?= $form->field($model, 'sub_time')->textInput() ?>

    <?= $form->field($model, 'sign_time')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num')->textInput() ?>

    <?= $form->field($model, 'transactor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'oldcontr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orgid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'userid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <?= $form->field($model, 'extra_data')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
