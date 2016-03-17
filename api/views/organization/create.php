<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\Organization */

$this->title = '创建组织';
$this->params['breadcrumbs'][] = ['label' => '组织', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
