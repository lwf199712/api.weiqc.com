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
use app\models\po\StaticConversionPo;
use app\models\po\StaticHitsPo;
use app\models\po\StaticServiceConversionsPo;
use app\models\po\StaticUrlPo;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticConversionImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticHitsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticUrlImpl;
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
    //工具类
    SourceDetectionUtil::class                       => SourceDetectionUtil::class,
    ResponseUtils::class                             => ResponseUtils::class,
    IpLocationUtils::class                           => IpLocationUtils::class,
    RequestUtils::class                              => RequestUtils::class,
    //API
    UserActionsApi::class                            => UserActionsApi::class,
    //po
    StaticServiceConversionsPo::class                => StaticServiceConversionsPo::class,
    StaticConversionPo::class                        => StaticConversionPo::class,
    StaticHitsPo::class                              => StaticHitsPo::class,
    StaticUrlPo::class                               => StaticUrlPo::class,
    RedisUtils::class                                => RedisUtils::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
