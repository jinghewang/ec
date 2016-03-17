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


<?php echo $this->render('_time2',['response'=>$response])?>


<?php echo $this->render('_date2',['response'=>$response])?>


