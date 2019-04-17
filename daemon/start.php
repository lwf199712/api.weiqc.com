<?php

use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;
use app\daemon\course\conversion\controller\ConversionController;
use app\daemon\course\conversion\service\CommandsStaticHitsService;
use app\modules\v1\userAction\enum\ConversionEnum;
use Workerman\Lib\Timer;
use Workerman\Worker;
use yii\base\InvalidConfigException;
use yii\di\Container;

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
        static $redisPopList = [];
        do {
            //容器
            /* @var Container $container */
            $container = Yii::$container;
            /* @var redisUtils $redisUtils */
            $redisUtils = $container->get(RedisUtils::class);
            //读取redis队列数据
            $redisAddViewPop = $redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW);
            if ($redisAddViewPop) {
                //保存数据到备份队列中
                $redisUtils->getRedis()->rpush(ConversionEnum::REDIS_ADD_VIEW_BACKUPS, [$redisAddViewPop]);
                //队列读取超过500条时触发上报
                $redisPopList[] = $redisAddViewPop;
                echo '当前长度' . count($redisPopList) . "\n";
                if (count($redisPopList) > 0) {
                    echo "执行操作\n";
                    try {
                        /* @var ArrayUtils $arrayUtils */
                        $arrayUtils = $container->get(ArrayUtils::class);
                        /* @var CommandsStaticHitsService $commandsStaticHitsService */
                        $commandsStaticHitsService = $container->get(CommandsStaticHitsService::class);
                        $conversionController = new ConversionController($redisUtils, $arrayUtils, $commandsStaticHitsService);
                        $conversionController->actionAddViews($redisPopList);
                        //成功时删除备份队列数据(注:不成功将保存该备份,该备份应由开发人员定期删除)
                        for ($num = 1, $numMax = count($redisPopList); $num <= $numMax; $num++) {
                            $redisUtils->getRedis()->rpop(ConversionEnum::REDIS_ADD_VIEW_BACKUPS);
                        }
                    } catch (Exception $e) {
                        //TODO 无法使用日志功能
                        echo $e->getMessage() . "\n";
                    }
                    unset($conversionController);
                    $redisPopList = [];
                }
            }
        } while ($redisAddViewPop);
    });
};
// 运行worker
Worker::runAll();