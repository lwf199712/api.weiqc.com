<?php

use app\daemon\conversionCommands\controller\ConversionCommandsController;
use Workerman\Lib\Timer;
use Workerman\Worker;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/console.php';

try {
    $application = new yii\console\Application($config);
} catch (\yii\base\InvalidConfigException $e) {
}

$worker = new Worker('tcp://0.0.0.0:8585');
$worker->count = 1;
$worker->onWorkerStart = static function () {

    //定时统计四个小时以前的订单
    $timeInterval = 4 * 60 * 60;
    Timer::add(10800, static function () use ($timeInterval) {

        $payOrder = new ConversionCommandsController();

        $payOrder->queryByOutTradeNumber($timeInterval);
    });


};
// 运行worker
Worker::runAll();