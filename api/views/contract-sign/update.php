<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */

$this->title = 'Update Contract Sign: ' . ' ' . $model->sign_id;
$this->params['breadcrumbs'][] = ['label' => 'Contract Signs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sign_id, 'url' => ['view', 'id' => $model->sign_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contract-sign-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
