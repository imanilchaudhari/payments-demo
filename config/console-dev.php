<?php

// Settings for console-application only
return [
    'bootstrap' => [
        'gii'
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],
        ]
    ],
];
