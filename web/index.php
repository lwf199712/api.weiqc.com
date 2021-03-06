<?php
header('Access-Control-Allow-Origin:' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
header('Access-Control-Allow-Headers:*');

// comment out the following two lines when deployed to production
use yii\base\InvalidConfigException;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
date_default_timezone_set('Asia/Shanghai');


try {
    (new yii\web\Application($config))->run();
} catch (InvalidConfigException $e) {
    throw new RuntimeException($e->getMessage(), $e->getCode());
}
