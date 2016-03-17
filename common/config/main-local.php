<?php
return [
    'language' => 'zh-CN', // 启用国际化支持
    'sourceLanguage' => 'zh-CN', // 源代码采用中文
    'timeZone' => 'Asia/Shanghai', // 设置时区
    'components' => [
        'request'=>array(
            'enableCsrfValidation'=>false,
        ),
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=ec',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',  //每种邮箱的host配置不一样
                'username' => 'btgerp@vive.net.cn',
                'password' => 'Demo@123',
                'port' => '25',
                'encryption' => 'tls',

            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['btgerp@vive.net.cn'=>'旅游电子合同']
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,//隐藏index.php
            //'enableStrictParsing' => true,
            //'suffix' => '.html',//后缀，如果设置了此项，那么浏览器地址栏就必须带上.html后缀，否则会报404错误
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['user', 'news','country']],
            ],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    'cachePath' => '@runtime/Smarty/cache',
                    'compilePath' => '@runtime/Smarty/compile',
                    'options' => [
                        //'php_handling'=>3,
                    ],
                    'pluginDirs' => [
                        '@app/../common/plugins',
                        '@app/../vendor/smarty/smarty/libs/plugins'
                    ],
                    //'left_delimiter' => '{{',
                    //'right_delimiter' => '}}',
                ],
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],
                // ...
            ],
        ],
        'pdf' => [
            'class' => kartik\mpdf\Pdf::classname(),
            // set to use core fonts only
            'mode' => kartik\mpdf\Pdf::MODE_UTF8,
            // A4 paper format
            'format' => kartik\mpdf\Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => kartik\mpdf\Pdf::DEST_BROWSER,
            // your html content input
            //'content' => '你好，世界',
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:24px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => '中文',
                'autoLangToFont' => true,    //这几个配置加上可以显示中文
                'autoScriptToLang' => true,  //这几个配置加上可以显示中文
                'autoVietnamese' => true,    //这几个配置加上可以显示中文
                'autoArabic' => true,        //这几个配置加上可以显示中文
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['中文'],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]
    ],
];
