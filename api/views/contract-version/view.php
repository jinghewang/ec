<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\ContractVersion */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Contract Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-version-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->vercode], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->vercode], [
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
            'vercode',
            'pcode',
            'title',
            'fileid',
            'orgid',
            'extra_data:ntext',
            'createuser',
            'createtime',
        ],
    ]) ?>

</div>
