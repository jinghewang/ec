<?php
/**
 * Created by PhpStorm.
 * User: huilai
 * Date: 16-1-7
 * Time: 上午9:37
 */
use yii\helpers\Html;
use api\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>神舟国内合同</title>
    <?=Html::cssFile('@web/css/ec.css')?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container" style="width: 800px">
    <div class="row">
        <div class="" style="color: blue;float: left;display: inline-block">.col-md-6</div>
        <div class="" style="color: red;float: left;display: inline-block">.col-md-6</div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

