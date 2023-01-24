<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'b_OBWIyY6Lu9OiQqjBBta6IsBGAqiiqE',
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true,
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => '600',
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => true,
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            // 'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            // 'authTimeout' => 60*30,
            'loginUrl' => ['admin/login'],
            // 'identityCookie' => [
            //     'name' => '_panelAdministrator',
            // ]
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'rules' => array(
                '' => 'main/index',
                'admin/?' => 'admin/main/index',
                'admin/dashboard' => 'admin/main/dashboard',
                'admin/domains' => 'admin/main/domain',
                'admin/domains/create' => 'admin/main/domain-create',
                'admin/domains/edit/<id:\d+>' => 'admin/main/domain-edit',
                'admin/domains/delete/<id:\d+>' => 'admin/main/domain-delete',
                'admin/languages' => 'admin/main/lang',
                'admin/languages/create' => 'admin/main/lang-create',
                'admin/languages/edit/<id:\d+>' => 'admin/main/lang-edit',
                'admin/languages/delete/<id:\d+>' => 'admin/main/lang-delete',
                'admin/languages/default/<id:\d+>' => 'admin/main/is-default',
                'admin/pages' => 'admin/main/page',
                'admin/pages/edit' => 'admin/main/page-edit',
                'admin/pages/delete' => 'admin/main/page-delete',
                'admin/orders' => 'admin/main/order',
                'admin/statistics' => 'admin/main/statistic',
                'admin/settings' => 'admin/main/setting',
                'admin/settings/edit/<id:\d+>' => 'admin/main/setting-edit',
                'admin/login' => 'admin/main/login',
                'admin/logout' => 'admin/main/logout',
                '/<language:\w+>/?' => 'main/index',
                'main/captcha' => 'main/captcha',
                [
                    'pattern' => '/index',
                    'route' => 'main/index',
                    'suffix' => '.html',
                ], 
                [
                    'pattern' => '<language:\w+>/index',
                    'route' => 'main/index',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '/checkout',
                    'route' => 'main/checkout',
                    'suffix' => '.html',
                ],   
                [
                    'pattern' => '<language:\w+>/checkout',
                    'route' => 'main/checkout',
                    'suffix' => '.html',
                ], 
                [
                    'pattern' => '/success',
                    'route' => 'main/success',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '<language:\w+>/success',
                    'route' => 'main/success',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '<language:\w+>/<page:\w+>',
                    'route' => 'main/view',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '/<page:\w+>',
                    'route' => 'main/view',
                    'suffix' => '.html',
                ],
                // '<controller:\w+>/<id:\d+>' => '<controller>/view',
                // '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
