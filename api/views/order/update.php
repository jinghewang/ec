<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\Order */

$this->title = '更细充值: ' . ' ' . $model->oid;
$this->params['breadcrumbs'][] = ['label' => '充值管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->oid, 'url' => ['view', 'id' => $model->oid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
