<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */

$this->title = '创建应用';
$this->params['breadcrumbs'][] = ['label' => '应用管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-app-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id'=>'countries'])?>

    <div class="access-app-form">

        <?php $form = ActiveForm::begin(['enableAjaxValidation'=>false,'options'=>['data-pjax'=>true]]); ?>

        <?= $form->field($model, 'appkey')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'appname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'client_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'client_secret')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success rb-btn-save' : 'btn btn-primary']) ?>
            <?php echo Html::a('test',['create'],['class'=>'btn btn-info'])?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php Pjax::end(); ?>


</div>


<script>
    $(function(){
       $('.rb-btn-save').on('click',function(event){
           console.info(event);
           $.pjax.submit(event,"#countries");
       });

        var container = $("#countries");//容器
        container.on('pjax:beforeSend',function(args){
           // ajax请求之前调用，返回false中断ajax请求
            //alert('before');
        });
        container.on('pjax:error',function(args){
            //ajax请求失败之后调用
            //alert('error');
        })
        container.on('pjax:success',function(args){
            //ajax请求成功之后调用
            //alert('succes');
        })
    });
</script>
