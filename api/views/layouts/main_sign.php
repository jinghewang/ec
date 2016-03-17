<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use api\assets\AppAsset;
use common\widgets\Alert;

$wxService = new \api\services\WxService();

AppAsset::register($this);
\hoter\fakeloader\FakeloaderAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!--  mobile Safari, FireFox, Opera Mobile  -->
    <script src="/jsignature/libs/modernizr.js"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jsignature/libs/flashcanvas.js"></script>
    <![endif]-->
    <script src="/jsignature/libs/jquery.js"></script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<style>
    .rb-sign-border{
        border: 1px solid blue;
        margin: 5px;
        padding: 5px;
    }
</style>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => \common\helpers\ConfigHelper::getAppConfig('name'),// 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
        $menuItems = [
        ['label' => '首页', 'url' => [$wxService->mainPage]],
        ['label' => '电子合同', 'url' => ['/contract/index2']],
    ];
    if (!$wxService->checkLogin()) {
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login2']];
    } else {
        $menuItems[] = [
            'label' => '退出('. $wxService->getLoginUser()->mobile .')',
            'url' => ['/site/logout2'],
            'linkOptions' => ['data-method' => 'post']
        ];

       /* $menuItems[] = [
            'label' => '解除绑定('. $wxService->getLoginUser()->mobile .')',
            'url' => ['/site/unbind'],
            'linkOptions' => ['data-method' => 'post']
        ];*/
    }

    $menuItems[] = [
        'label' => '刷新',
        //'url' => ['#'],
        'id' => 'rb-refresh',
        'options'=>['class' => ['rb-refresh-link']],
    ];

    $menuItems[] = [
        'label' => '调试',
        'url' => ['/site/debug'],
    ];

    $menuItems[] = [
        'label' => '清空',
        'url' => ['/site/debug-clear'],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container" style="padding-left:5px;padding-right:5px;width: 100%">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<script>
    $('.rb-refresh-link').on('click',function(e){
        //console.info('reload');
        window.location.reload();
    })
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
