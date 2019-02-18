<?php

// Settings for web-application only
return [
    'components' => [
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' => 1000000,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => '_identity-app', 'httpOnly' => true],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'trace', 'warning', 'error'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'trace', 'warning'],
                    'categories' => ['tranx'],
                    'logFile' => '@runtime/tranx/app.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'categories' => ['tranx'],
                    'logFile' => '@runtime/tranx/error.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ]
            ],
        ],
        'request' => [
            'cookieValidationKey' => getenv('APP_COOKIE_VALIDATION_KEY'),
        ],
        'urlManager' => [
            'enablePrettyUrl' => getenv('APP_PRETTY_URLS'),
            'showScriptName' => getenv('APP_SCRIPT_NAME'),
            'rules' => require 'rules.php',
        ]
    ],
];
