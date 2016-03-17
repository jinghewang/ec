<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="hidden">

    <?= $form->field($model, 'out_trade_no')->textInput() ?>

    <?= $form->field($model, 'appkey')->textInput() ?>

    </div>

    <?= $form->field($model, 'paynum')->textInput() ?>

    <?= $form->field($model, 'paysum')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?php ActiveForm::end(); ?>

</div>


<script>
    $(function(){

        $('#order-paynum').on('input',function(e){
            $('#order-paysum').val($('#order-paynum').val()*0.01);
        })

    });
</script>
