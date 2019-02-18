<?php
return [
    [
        'pattern' => '<alias:captcha>',
        'route'   => 'site/<alias>',
    ],
    '<controller:\w+>/<id:\d+>' => '<controller>/view',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
];
