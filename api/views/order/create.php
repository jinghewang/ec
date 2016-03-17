<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\Order */

$this->title = '充值';
$this->params['breadcrumbs'][] = ['label' => '充值管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
