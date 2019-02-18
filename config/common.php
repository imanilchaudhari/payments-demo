<?php

// Basic configuration, used in web and console applications
return [
    'id' => 'C2I',
    'name' => 'C2I',
    'language' => 'en',
    'basePath' => dirname(__DIR__).'/app',
    'vendorPath' => '@vendor',
    'runtimePath' => '@runtime',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    // Bootstrapped modules are loaded in every request
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => getenv('MAILER_USE_FILE_TRANSPORT')
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
    ],
    'params' => require 'params.php',
];
