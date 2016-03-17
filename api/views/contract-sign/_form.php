<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-sign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contr_id')->textInput() ?>

    <?= $form->field($model, 'sign_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_userid')->textInput() ?>

    <?= $form->field($model, 'sign_time')->textInput() ?>

    <?= $form->field($model, 'sign_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sign_sign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_file')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'openid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bindtime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
