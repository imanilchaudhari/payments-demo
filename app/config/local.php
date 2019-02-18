<?php

return [
    'name' => 'C2I',
    'bootstrap' => ['log', 'assetsAutoCompress'],
    'components' => [
        'assetsAutoCompress' => [
            'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'enabled' => getenv('APP_ASSETS_COMPRESS')
        ],
        'ppl'=> [
            'class'        => 'app\classes\Paypal',
            'clientId'     => getenv('PAYPAL_CLIENT_ID'),
            'clientSecret' => getenv('PAYPAL_CLIENT_SECRET'),
            'isProduction' => false,
            'config'       => [
                'http.ConnectionTimeOut' => 30,
                'http.Retry'             => 1,
                'mode'                   => getenv('PAYPAL_MODE'), // development (sandbox) or production (live) mode
                'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
                'log.FileName'           => Yii::getAlias('@runtime/paypal/app.log'),
                'log.LogLevel'           => getenv('PAYPAL_LOG_LEVEL'),
                'cache.enabled'          => 'true',
                'cache.FileName'         => Yii::getAlias('@runtime/paypal/auth.cache'),
            ]
        ]
    ]
];
