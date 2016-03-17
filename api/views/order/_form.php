<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'appkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orgid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createuserid')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'out_trade_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paysum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paynum')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'post1')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'post2')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'posttime')->textInput() ?>

    <?= $form->field($model, 'result')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'resulttime')->textInput() ?>

    <?= $form->field($model, 'callback')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'callbacktime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
