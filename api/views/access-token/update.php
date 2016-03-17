<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\AccessToken */

$this->title = '更新 Token: ' . ' ' . $model->tokenid;
$this->params['breadcrumbs'][] = ['label' => 'Access Tokens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tokenid, 'url' => ['view', 'id' => $model->tokenid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="access-token-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
