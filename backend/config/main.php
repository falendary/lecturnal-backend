<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/main.php')
);


return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'vendorPath' => __DIR__ . '/../..' . '/vendor',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'baseUrl' => '/backend/web/',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'errorHandler' => [
//            'errorAction' => 'site/error',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=server36.hosting.reg.ru;port=3306;dbname=u0271436_default',
            'username' => 'u0271436_default',
            'password' => '9jVr1_ER',
            'charset' => 'utf8',
            'attributes' => [
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' =>
                [
                    'GET /v1/presentations' => 'presentation/index',
                    'GET /v1/slides' => 'slide',
                ]
        ],

    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module'
        ]
    ],
    'params' => $params,
];
