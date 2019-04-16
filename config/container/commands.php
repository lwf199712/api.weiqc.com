<?php
/**
 * 容器注册(只为commands代码服务)
 *
 * @author lirong
 */

//conversionCommands容器,标记为注册conversionCommands容器
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\commands\conversionCommands\service\impl\CommandsCommandsStaticHitsImpl;
use app\commands\utils\CommandsBatchInsertUtils;
use app\models\po\StaticHitsPo;
use app\utils\ArrayUtils;
use app\utils\RedisUtils;

$containerList = [
    //接口容器
    CommandsStaticHitsService::class => CommandsCommandsStaticHitsImpl::class,
    UserActionsService::class        => UserActionsImpl::class,
    //工具
    ArrayUtils::class                => ArrayUtils::class,
    RedisUtils::class                => RedisUtils::class,
    CommandsBatchInsertUtils::class  => CommandsBatchInsertUtils::class,
    //po
    StaticHitsPo::class              => StaticHitsPo::class,

];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
