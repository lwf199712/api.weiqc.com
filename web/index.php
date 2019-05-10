<?php

// comment out the following two lines when deployed to production
use app\common\utils\StringUtil;
use yii\base\InvalidConfigException;

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

//跨域
$httpOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($httpOrigin) {
    $httpOrigin = stringUtil::cutOutLater($httpOrigin, 'https://');
    $httpOrigin = stringUtil::cutOutFormer($httpOrigin, 'http://');
    $httpOrigin = stringUtil::cutOutFormer($httpOrigin, '.');
    if (in_array($httpOrigin, Yii::$app->params['cross_domain'], false)) {
        header('Access-Control-Allow-Origin:' . $httpOrigin);
        header('Access-Control-Allow-Headers:' . $httpOrigin);
    }
}

try {
    (new yii\web\Application($config))->run();
} catch (InvalidConfigException $e) {
    throw new RuntimeException($e->getMessage(), $e->getCode());
}
