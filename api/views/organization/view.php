<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\Organization */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '组织', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->orgid], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'enname',
            'vercode',
            'createuserid',
            'createtime',
            'extra_data:ntext',
        ],
    ]) ?>

</div>
