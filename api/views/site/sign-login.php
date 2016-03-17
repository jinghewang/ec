<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use api\services\WxService;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
$wxService = new WxService();
$logined = $wxService->checkLogin();
$loginUser = $wxService->getLoginUser();

?>
<div class="site-login">
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

   <!-- <p>请填写以下内容：</p>-->

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'mobile') ?>

                <?= $form->field($model, 'code') ?>

                <div class="hidden">
                    <?= $form->field($model, 'openid')->textInput() ?>
                </div>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    忘记密码，点这里 <?= Html::a('重置', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('立即登录', ['class' => 'btn btn-primary', 'name' => 'login-button','style'=>'width:100%']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>

    $(function(){

    });
</script>