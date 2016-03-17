<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\ContractVersion */

$this->title = 'Create Contract Version';
$this->params['breadcrumbs'][] = ['label' => 'Contract Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-version-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
