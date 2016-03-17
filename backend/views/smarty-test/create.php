<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SmartyTest */

$this->title = 'Create Smarty Test';
$this->params['breadcrumbs'][] = ['label' => 'Smarty Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smarty-test-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
