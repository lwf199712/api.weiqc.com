<?php

/**
 * 容器注册(只为v1代码服务)
 *
 * @author lirong
 */

//userAction容器,标记为注册userAction容器
use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\common\utils\UrlUtils;
use app\models\dataObject\StaticConversionDo;
use app\models\dataObject\StaticHitsDo;
use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\userAction\service\impl\UserActionRedisCacheImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticConversionImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticHitsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticUrlImpl;
use app\modules\v1\userAction\service\UserActionCache;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticServiceConversionsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use app\common\utils\IpLocationUtils;
use app\common\utils\RedisUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\common\utils\SourceDetectionUtil;

$containerList = [
    //接口容器
    UserActionStaticUrlService::class                => UserActionUserActionStaticUrlImpl::class,
    UserActionStaticConversionService::class         => UserActionUserActionStaticConversionImpl::class,
    UserActionStaticServiceConversionsService::class => UserActionUserActionStaticServiceConversionsImpl::class,
    UserActionsService::class                        => UserActionsImpl::class,
    UserActionStaticHitsService::class               => UserActionUserActionStaticHitsImpl::class,
    UserActionCache::class                           => UserActionRedisCacheImpl::class,
    //工具类
    SourceDetectionUtil::class                       => SourceDetectionUtil::class,
    ResponseUtils::class                             => ResponseUtils::class,
    IpLocationUtils::class                           => IpLocationUtils::class,
    RequestUtils::class                              => RequestUtils::class,
    UrlUtils::class                                  => UrlUtils::class,
    //API
    UserActionsApi::class                            => UserActionsApi::class,
    //po
    StaticServiceConversionsDo::class                => StaticServiceConversionsDo::class,
    StaticConversionDo::class                        => StaticConversionDo::class,
    StaticHitsDo::class                              => StaticHitsDo::class,
    StaticUrlDo::class                               => StaticUrlDo::class,
    RedisUtils::class                                => RedisUtils::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
