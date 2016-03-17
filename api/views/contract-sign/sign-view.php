<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */
/* @var $form yii\widgets\ActiveForm */

$this->title = '操作成功';
$this->params['breadcrumbs'][] = ['label' => 'Contract Signs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="contract-sign-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div id="show" class="rb-sign-border text-center">
        <img src="<?= $model->sign_data?>">
    </div>

    <div class="form-group text-center">
        <?= Html::button('关闭', ['class' => 'btn btn-primary rb-sign-back']) ?>
    </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.rb-sign-back').on('click',function(e){
            window.close();
        });
    })
</script>