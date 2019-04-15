<?php

/**
 * 容器注册(只为v1代码服务)
 *
 * @author lirong
 */

use app\api\tencentMarketingApi\userActions\api\UserActionsAip;
use app\api\tencentMarketingAPI\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingAPI\userActions\service\UserActionsService;
use app\modules\v1\userAction\domain\po\StaticConversionPo;
use app\modules\v1\userAction\domain\po\StaticHitsPo;
use app\modules\v1\userAction\domain\po\StaticServiceConversionsPo;
use app\modules\v1\userAction\domain\po\StaticUrlPo;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticConversionImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticHitsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticUrlImpl;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticServiceConversionsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use app\utils\IpLocationUtils;
use app\utils\RedisUtils;
use app\utils\RequestUtils;
use app\utils\ResponseUtils;
use app\utils\SourceDetectionUtil;

$containerList = [
    //接口容器
    UserActionStaticUrlService::class                => UserActionUserActionStaticUrlImpl::class,
    UserActionStaticConversionService::class         => UserActionUserActionStaticConversionImpl::class,
    UserActionStaticServiceConversionsService::class => UserActionUserActionStaticServiceConversionsImpl::class,
    UserActionsService::class                        => UserActionsImpl::class,
    UserActionStaticHitsService::class               => UserActionUserActionStaticHitsImpl::class,
    //工具类
    SourceDetectionUtil::class                       => SourceDetectionUtil::class,
    ResponseUtils::class                             => ResponseUtils::class,
    IpLocationUtils::class                           => IpLocationUtils::class,
    RequestUtils::class                              => RequestUtils::class,
    //API
    UserActionsAip::class                            => UserActionsAip::class,
    //DTO
    StaticServiceConversionsPo::class        => StaticServiceConversionsPo::class,
    StaticConversionPo::class                => StaticConversionPo::class,
    StaticHitsPo::class                    => StaticHitsPo::class,
    StaticUrlPo::class                     => StaticUrlPo::class,
    RedisUtils::class                      => RedisUtils::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
