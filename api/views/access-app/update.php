<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */

$this->title = '更新应用: ' . ' ' . $model->appkey;
$this->params['breadcrumbs'][] = ['label' => '应用', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->appkey, 'url' => ['view', 'id' => $model->appkey]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="access-app-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
