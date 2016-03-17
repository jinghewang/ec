<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use api\assets\AppAsset;
use common\widgets\Alert;
use \common\helpers\ConfigHelper;
use \common\helpers\BDataHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => ConfigHelper::getAppConfig('name'),// 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],

//        ['label' => '联系', 'url' => ['/site/contact']],
        /*['label' => '关于', 'url' => ['/site/about']],*/
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '注册', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label'=>'合同','url'=>['/contract/index']];
        $menuItems[] = ['label' => '应用', 'url' => ['/access-app/index']];
        $menuItems[] = ['label' => 'Token', 'url' => ['/access-token/index']];
        $menuItems[] = ['label' => '充值', 'url' => ['/order/index']];
        $menuItems[] = ['label' => '组织', 'url' => ['/organization/index']];
        $menuItems[] = ['label' => '权限', 'url' => ['/admin/default/index']];
        $menuItems[] = [
            'label' => '退出 (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }

    $menuItems=BDataHelper::filter_menu_access($menuItems);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?php echo ConfigHelper::getCompanyConfig('copyright') ?> <?= date('Y') ?></p>

        <p class="pull-right"><?php echo ConfigHelper::getCompanyConfig('powered') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
