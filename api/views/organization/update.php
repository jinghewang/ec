<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\Organization */

$this->title = '更新: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '组织', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->orgid]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="organization-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
