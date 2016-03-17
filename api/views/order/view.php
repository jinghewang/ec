<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\Order */

$this->title = $model->oid;
$this->params['breadcrumbs'][] = ['label' => '充值', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->oid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->oid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'oid',
            'appkey',
            'orgid',
            'createuserid',
            'createtime',
            'out_trade_no',
            'paysum',
            'paynum',
            'status',
            'post1:ntext',
            'post2:ntext',
            'posttime',
            'result:ntext',
            'resulttime',
            'callback:ntext',
            'callbacktime',
        ],
    ]) ?>

</div>
