<?php
/**
 * 容器注册(只为commands代码服务)
 *
 * @author lirong
 */

//conversionCommands容器,标记为注册conversionCommands容器
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\commands\conversionCommands\service\CommandsStaticUrlService;
use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\commands\conversionCommands\service\impl\CommandsCommandsStaticHitsImpl;
use app\commands\conversionCommands\service\impl\CommandsStaticUrlImpl;
use app\commands\utils\CommandsBatchInsertUtils;
use app\models\dataObject\StaticHitsDo;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;

$containerList = [
    //接口容器
    CommandsStaticHitsService::class => CommandsCommandsStaticHitsImpl::class,
    CommandsStaticUrlService::class  => CommandsStaticUrlImpl::class,
    UserActionsService::class        => UserActionsImpl::class,
    //工具
    ArrayUtils::class                => ArrayUtils::class,
    RedisUtils::class                => RedisUtils::class,
    CommandsBatchInsertUtils::class  => CommandsBatchInsertUtils::class,
    //po
    StaticHitsDo::class              => StaticHitsDo::class,

];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
