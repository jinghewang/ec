<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\AccessApp */

$this->title = '创建应用';
$this->params['breadcrumbs'][] = ['label' => '应用管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-app-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //echo Html::errorSummary($model); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
