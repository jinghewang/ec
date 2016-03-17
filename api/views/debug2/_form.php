<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-app-form">


    <?php $form = ActiveForm::begin(['enableAjaxValidation'=>true,'options'=>['data-pjax'=>true]]); ?>

    <?= $form->field($model, 'appkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appname')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success rb-modal-save-btn' : 'btn btn-primary rb-modal-save-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
