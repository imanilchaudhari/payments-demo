<?php

// Settings for web-application only
return [
    'bootstrap' => [
        'gii',
        'debug',
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['*'],
            'panels' => [
                'elasticsearch' => [
                    'class' => 'yii\\elasticsearch\\DebugPanel',
                ],
                'httpclient' => [
                    'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
                ],
            ],
        ],
    ],
];
