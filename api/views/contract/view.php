<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\Contract */

$this->title = $model->contr_id;
$this->params['breadcrumbs'][] = ['label' => 'Contracts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->contr_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->contr_id], [
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
            'contr_id',
            'contr_no',
            'vercode',
            'type',
            'is_lock',
            'lock_time',
            'status',
            'audit_status',
            'is_submit',
            'sub_time',
            'sign_time',
            'price',
            'num',
            'transactor',
            'oldcontr',
            'orgid',
            'createtime',
            'userid',
            'modified',
            'extra_data:ntext',
        ],
    ]) ?>

</div>
