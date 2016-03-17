<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model api\models\Order */

$this->title = 'Pay Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <?= $this->render('_pay_form', [
        'model' => $model,
    ]) ?>

</div>
