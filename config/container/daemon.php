<?php
/**
 * 容器注册(只为daemon代码服务)
 *
 * @author lirong
 */

use app\api\tencentMarketingApi\oauth\service\impl\OauthImpl;
use app\api\tencentMarketingApi\oauth\service\impl\OauthRedisCacheImpl;
use app\api\tencentMarketingApi\oauth\service\OauthCacheService;
use app\api\tencentMarketingApi\oauth\service\OauthService;
use app\api\tencentMarketingApi\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingApi\userActions\service\UserActionsService;
use app\daemon\common\utils\CommandsBatchInsertUtils;
use app\daemon\course\conversion\service\CourseStaticHitsService;
use app\daemon\course\conversion\service\impl\CommandsCourseStaticHitsImpl;
use app\daemon\course\conversion\service\CommandsStaticUrlService;
use app\daemon\course\conversion\service\impl\CommandsStaticUrlImpl;
use app\models\dataObject\StaticHitsDo;
use app\common\utils\ArrayUtils;
use app\common\utils\RedisUtils;

$containerList = [
    //urlConvert模块容器
    CommandUrlConvertService::class => CommandUrlConvertImpl::class,
    //conversion模块容器
    CourseStaticHitsService::class  => CommandsCourseStaticHitsImpl::class,
    CommandsStaticUrlService::class => CommandsStaticUrlImpl::class,
    UserActionsService::class       => UserActionsImpl::class,
    OauthService::class             => OauthImpl::class,
    OauthCacheService::class        => OauthRedisCacheImpl::class,
    //工具
    ArrayUtils::class               => ArrayUtils::class,
    RedisUtils::class               => RedisUtils::class,
    CommandsBatchInsertUtils::class => CommandsBatchInsertUtils::class,
    //po
    StaticHitsDo::class             => StaticHitsDo::class,

];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
