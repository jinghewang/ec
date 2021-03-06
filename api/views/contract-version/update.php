<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\ContractVersion */

$this->title = 'Update Contract Version: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Contract Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->vercode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contract-version-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
