<?php
/* @var $this yii\web\View */
use yii\widgets\Pjax;
use yii\helpers\Html;

?>


<?php Pjax::begin(['id' => 'dates']) ?>
    <?= Html::a('show date2', ['date2'], ['class' => 'btn btn-lg btn-info']) ?>
    <h1>It's: <?= $response ?></h1>
<?php Pjax::end() ?>
