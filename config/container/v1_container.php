<?php

/**
 * 容器注册(只为v1代码服务)
 *
 * @author lirong
 */

use app\api\tencentMarketingApi\oauth\service\impl\OauthImpl;
use app\api\tencentMarketingApi\oauth\service\impl\OauthRedisCacheImpl;
use app\api\tencentMarketingApi\oauth\service\OauthCacheService;
use app\api\tencentMarketingApi\oauth\service\OauthService;
use app\api\tencentMarketingApi\userActions\api\UserActionsApi;
use app\api\tencentMarketingApi\userActions\service\impl\UserActionsImpl;
use app\api\tencentMarketingApi\userActions\service\UserActionsService;
use app\api\tencentMarketingApi\userActionSets\service\impl\UserActionSetsImpl;
use app\api\tencentMarketingApi\userActionSets\service\UserActionSetsService;
use app\common\infrastructure\service\impl\SMSImpl;
use app\common\infrastructure\service\SMS;
use app\common\utils\UrlUtils;
use app\models\dataObject\SectionRealtimeMsgDo;
use app\models\dataObject\StaticConversionDo;
use app\models\dataObject\StaticHitsDo;
use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;
use app\modules\v1\autoConvert\service\AutoConvertSectionRealtimeMsgService;
use app\modules\v1\autoConvert\service\AutoConvertService;
use app\modules\v1\autoConvert\service\AutoConvertStaticConversionService;
use app\modules\v1\autoConvert\service\AutoConvertStaticUrlService;
use app\modules\v1\autoConvert\service\CalculateLackFansRateService;
use app\modules\v1\autoConvert\service\ChangeService;
use app\modules\v1\autoConvert\service\impl\AutoConvertSectionRealtimeMsgServiceImpl;
use app\modules\v1\autoConvert\service\impl\AutoConvertServiceImpl;
use app\modules\v1\autoConvert\service\impl\AutoConvertStaticConversionServiceImpl;
use app\modules\v1\autoConvert\service\impl\AutoConvertStaticUrlServiceImpl;
use app\modules\v1\autoConvert\service\impl\CalculateLackFansRateServiceImpl;
use app\modules\v1\autoConvert\service\impl\ChangeServiceImpl;
use app\modules\v1\userAction\service\impl\UserActionPageMonitorImpl;
use app\modules\v1\userAction\service\impl\UserActionRedisCacheImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticConversionImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticHitsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\UserActionUserActionStaticUrlImpl;
use app\modules\v1\userAction\service\UserActionCache;
use app\modules\v1\userAction\service\UserActionPageMonitorService;
use app\modules\v1\userAction\service\UserActionStaticConversionService;
use app\modules\v1\userAction\service\UserActionStaticHitsService;
use app\modules\v1\userAction\service\UserActionStaticServiceConversionsService;
use app\modules\v1\userAction\service\UserActionStaticUrlService;
use app\common\utils\IpLocationUtils;
use app\common\utils\RedisUtils;
use app\common\utils\RequestUtils;
use app\common\utils\ResponseUtils;
use app\common\utils\SourceDetectionUtil;
use Symfony\Component\EventDispatcher\EventDispatcher;

$containerList = [
    //公共容器:工具类
    SourceDetectionUtil::class                       => SourceDetectionUtil::class,
    ResponseUtils::class                             => ResponseUtils::class,
    IpLocationUtils::class                           => IpLocationUtils::class,
    RequestUtils::class                              => RequestUtils::class,
    UrlUtils::class                                  => UrlUtils::class,
    RedisUtils::class                                => RedisUtils::class,
    //公共容器:API
    UserActionsApi::class                            => UserActionsApi::class,
    //公共容器:po
    SectionRealtimeMsgDo::class                      => SectionRealtimeMsgDo::class,
    StaticServiceConversionsDo::class                => StaticServiceConversionsDo::class,
    StaticConversionDo::class                        => StaticConversionDo::class,
    StaticHitsDo::class                              => StaticHitsDo::class,
    StaticUrlDo::class                               => StaticUrlDo::class,
    //公共容器:事件派遣
    EventDispatcher::class                           => EventDispatcher::class,
    //公共容器：基础设施
    SMS::class                                       => SMSImpl::class,
    //userAction模块容器
    UserActionStaticUrlService::class                => UserActionUserActionStaticUrlImpl::class,
    UserActionStaticConversionService::class         => UserActionUserActionStaticConversionImpl::class,
    UserActionStaticServiceConversionsService::class => UserActionUserActionStaticServiceConversionsImpl::class,
    UserActionsService::class                        => UserActionsImpl::class,
    UserActionStaticHitsService::class               => UserActionUserActionStaticHitsImpl::class,
    UserActionPageMonitorService::class              => UserActionPageMonitorImpl::class,
    //autoConvert模块容器
    CalculateLackFansRateService::class              => CalculateLackFansRateServiceImpl::class,
    ChangeService::class                             => ChangeServiceImpl::class,
    AutoConvertStaticUrlService::class               => AutoConvertStaticUrlServiceImpl::class,
    AutoConvertStaticConversionService::class        => AutoConvertStaticConversionServiceImpl::class,
    AutoConvertService::class                        => AutoConvertServiceImpl::class,
    AutoConvertSectionRealtimeMsgService::class      => AutoConvertSectionRealtimeMsgServiceImpl::class,
    //oauth模块容器
    UserActionCache::class                           => UserActionRedisCacheImpl::class,
    //tencentMarketingApi - oauth 容器...
    OauthCacheService::class                         => OauthRedisCacheImpl::class,
    OauthService::class                              => OauthImpl::class,
    UserActionSetsService::class                     => UserActionSetsImpl::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
