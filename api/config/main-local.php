<?php

$config = [
    'defaultRoute'=>'/site/index',
    'aliases' => [
        '@mdm/admin' => '$PATH\yii2-admin-2.0.0',
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',//yii2-admin的导航菜单
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'r6ajOGWbKsDnIybrzMrw-5sY_do8tca_',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
            'defaultRoles' => ['operator'],//添加此行代码，指定默认规则为 '未登录用户'
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',//允许访问的节点，可自行添加
            'contract-version/*',
            'contract/index2',
            'contract-sign/*',
            /*'admin/*',*/
            'debug/*',
            'debug2/*'
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
