<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractVersion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vercode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fileid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orgid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extra_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'createuser')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
