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
use app\modules\v1\userAction\service\impl\StaticConversionImpl;
use app\modules\v1\userAction\service\impl\StaticHitsImpl;
use app\modules\v1\userAction\service\impl\StaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\StaticUrlImpl;
use app\modules\v1\userAction\service\StaticConversionService;
use app\modules\v1\userAction\service\StaticHitsService;
use app\modules\v1\userAction\service\StaticServiceConversionsService;
use app\modules\v1\userAction\service\StaticUrlService;
use app\utils\IpLocationUtils;
use app\utils\RedisUtils;
use app\utils\RequestUtils;
use app\utils\ResponseUtils;
use app\utils\SourceDetectionUtil;

$containerList = [
    //接口容器
    StaticUrlService::class                => StaticUrlImpl::class,
    StaticConversionService::class         => StaticConversionImpl::class,
    StaticServiceConversionsService::class => StaticServiceConversionsImpl::class,
    UserActionsService::class              => UserActionsImpl::class,
    StaticHitsService::class               => StaticHitsImpl::class,
    //工具类
    SourceDetectionUtil::class             => SourceDetectionUtil::class,
    ResponseUtils::class                   => ResponseUtils::class,
    IpLocationUtils::class                 => IpLocationUtils::class,
    RequestUtils::class                    => RequestUtils::class,
    //API
    UserActionsAip::class                  => UserActionsAip::class,
    //DTO
    StaticServiceConversionsPo::class      => StaticServiceConversionsPo::class,
    StaticConversionPo::class              => StaticConversionPo::class,
    StaticHitsPo::class                    => StaticHitsPo::class,
    StaticUrlPo::class                     => StaticUrlPo::class,
    RedisUtils::class                      => RedisUtils::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
