<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */

$this->title = $model->appkey;
$this->params['breadcrumbs'][] = ['label' => '应用管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-app-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->appkey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->appkey], [
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
            'appkey',
            'appname',
            'client_id',
            'client_secret',
            'created',
            'modified',
        ],
    ]) ?>

</div>
