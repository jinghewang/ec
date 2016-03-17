<?php
/* @var $this yii\web\View */
use yii\widgets\Pjax;
use yii\helpers\Html;

?>
<?php Pjax::begin(['id' => 'times']) ?>
    <?= Html::a('show time2', ['time2'], ['class' => 'btn btn-lg btn-primary']) ?>
    <h1>It's: <?= $response ?></h1>

<?php Pjax::end() ?>
