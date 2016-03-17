<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */

$this->title = 'Create Contract Sign';
$this->params['breadcrumbs'][] = ['label' => 'Contract Signs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-sign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
