<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\helpers\BDataHelper;
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

<?php //\common\helpers\BDataHelper::print_r(Yii::$app->request->queryParams);?>

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
        ['label' => '首页', 'url' => ['/wx-js/index']],
        ['label' => 'JSSDK DEMO', 'url' => 'http://demo.open.weixin.qq.com/jssdk'],
        ['label' => '我的位置(微信)', 'url' => ['/wx-js/loc']],
        ['label' => '我的位置(百度)', 'url' => ['/wx-js/baidu']],
        ['label' => '扫一扫', 'url' => ['/wx-js/scan']],
        ['label' => 'Test', 'url' => ['/wx-js/test']],
        ['label' => '调试', 'url' => ['/wx-js/debug']],
        ['label' => '刷新', 'id' => 'rb-refresh', 'options' => ['class' => ['rb-refresh-link']]],
    ];
    if (!$wxService->checkLogin()) {
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login2']];
    } else {
        $menuItems[] = [
            'label' => '退出('. $wxService->getLoginUser()->mobile .')',
            'url' => ['/site/logout2'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }

    for ($i = 0; $i < count($menuItems); $i++) {
        $item = $menuItems[$i];
        if (!empty($item) && !empty($item['url']) && is_array($item['url'])) {
            $menuItems[$i]['url'] = $item['url'] + Yii::$app->request->queryParams;
        }
    }

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
        alert('reload:'+ window.location.href);
        window.location = window.location.href;
    })
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
