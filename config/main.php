<?php

// Define application aliases
Yii::setAlias('@app', realpath(__DIR__));
Yii::setAlias('@root', dirname(__DIR__));
Yii::setAlias('@web', '@root/web');
Yii::setAlias('@runtime', '@root/runtime');
Yii::setAlias('@vendor', '@root/vendor');

return \yii\helpers\ArrayHelper::merge(
    require(__DIR__."/common.php"),
    require(__DIR__."/" . APP_TYPE .".php"),
    (file_exists(__DIR__."/" . APP_TYPE ."-" . YII_ENV . ".php")) ? require(__DIR__."/" . APP_TYPE ."-" . YII_ENV . ".php") : [],
    (file_exists(getenv('APP_CONFIG_FILE'))) ? require(getenv('APP_CONFIG_FILE')) : []
);
