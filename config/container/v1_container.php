<?php

/**
 * 容器注册(只为v1代码服务)
 *
 * @author lirong
 */

use app\api\tencentMarketingAPI\oauth\service\impl\OauthImpl;
use app\api\tencentMarketingAPI\oauth\service\impl\OauthRedisCacheImpl;
use app\api\tencentMarketingApi\oauth\service\OauthCacheService;
use app\api\tencentMarketingAPI\oauth\service\OauthService;
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
    //公共容器:工具类
    SourceDetectionUtil::class                       => SourceDetectionUtil::class,
    ResponseUtils::class                             => ResponseUtils::class,
    IpLocationUtils::class                           => IpLocationUtils::class,
    RequestUtils::class                              => RequestUtils::class,
    UrlUtils::class                                  => UrlUtils::class,
    //公共容器:API
    UserActionsApi::class                            => UserActionsApi::class,
    //公共容器:po
    StaticServiceConversionsDo::class                => StaticServiceConversionsDo::class,
    StaticConversionDo::class                        => StaticConversionDo::class,
    StaticHitsDo::class                              => StaticHitsDo::class,
    StaticUrlDo::class                               => StaticUrlDo::class,
    RedisUtils::class                                => RedisUtils::class,
    //userAction模块容器
    UserActionStaticUrlService::class                => UserActionUserActionStaticUrlImpl::class,
    UserActionStaticConversionService::class         => UserActionUserActionStaticConversionImpl::class,
    UserActionStaticServiceConversionsService::class => UserActionUserActionStaticServiceConversionsImpl::class,
    UserActionsService::class                        => UserActionsImpl::class,
    UserActionStaticHitsService::class               => UserActionUserActionStaticHitsImpl::class,
    //oauth模块容器
    UserActionCache::class                           => UserActionRedisCacheImpl::class,
    //tencentMarketingApi - oauth 容器
    OauthCacheService::class                         => OauthRedisCacheImpl::class,
    OauthService::class                              => OauthImpl::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
