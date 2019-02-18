<?php

return [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    'components'=>[
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'trace', 'warning', 'error'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => getenv('APP_PRETTY_URLS'),
            'showScriptName' => getenv('APP_SCRIPT_NAME'),
            'hostInfo' => getenv('APP_BASE_URL'),
            'baseUrl' => getenv('APP_BASE_URL'),
            'rules' => require 'rules.php',
        ]
    ]
];
