<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */

$this->title = $model->sign_id;
$this->params['breadcrumbs'][] = ['label' => 'Contract Signs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-sign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sign_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sign_id], [
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
            'sign_id',
            'contr_id',
            'sign_name',
            'sign_userid',
            'sign_time:datetime',
            'sign_data:ntext',
            'sign_sign',
            'sign_file',
            'mobile',
            'code',
            'email:email',
            'openid',
            'bindtime',
        ],
    ]) ?>

</div>
