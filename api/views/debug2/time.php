<?php
/* @var $this yii\web\View */
use yii\widgets\Pjax;
use yii\helpers\Html;

?>
<h1>debug/index</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>


<?php Pjax::begin(['id' => 'times']) ?>
    1111
    <?= Html::a('show time', ['time'], ['class' => 'btn btn-lg btn-primary']) ?>
    <?= Html::a('show date', ['date'], ['class' => 'btn btn-lg btn-info']) ?>
    <h1>It's: <?= $response ?></h1>

<?php Pjax::end() ?>
