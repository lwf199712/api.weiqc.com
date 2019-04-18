<?php

use app\daemon\course\conversion\ConversionApplication;
use Workerman\Lib\Timer;
use Workerman\Worker;
use yii\base\InvalidConfigException;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/daemon.php';

try {
    $application = new yii\console\Application($config);
} catch (InvalidConfigException $e) {
    exit;
}
//启动定时器
$worker = new Worker('tcp://0.0.0.0:8585');
$worker->count = 1;
$worker->onWorkerStart = static function () {
    Timer::add(0.5, static function () {
        ConversionApplication::addViewsWorkMan();
    });
};
// 运行worker
Worker::runAll();