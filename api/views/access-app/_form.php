<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-app-form">

    <?php $form = ActiveForm::begin(['id'=>'app-create-form']); ?>

    <?= $form->field($model, 'appkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'client_secret')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eccount')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::button($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success rb-modal-app-save' : 'btn btn-primary rb-modal-app-save']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
