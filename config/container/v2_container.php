<?php declare(strict_types=1);

/**
 * 容器注册(只为v2代码服务)
 *
 * @author zhuozhen
 */

use app\api\toutiaoMarketingApi\oauth\service\impl\OauthImpl;
use app\api\uacApi\service\impl\OauthImpl as UacOauthImpl;
use app\api\uacApi\service\impl\UserImpl as UacUserImpl;
use app\api\uacApi\service\Oauth as UacOauthService;
use app\api\uacApi\service\User as UacUserService;
use app\common\infrastructure\service\DataSetCalculateService;
use app\common\infrastructure\service\ExcelService;
use app\common\infrastructure\service\impl\DataSetCalculateImpl;
use app\common\infrastructure\service\impl\ExcelServiceImpl;
use \app\api\toutiaoMarketingApi\oauth\service\Oauth as OauthService;
use app\common\infrastructure\service\impl\TimeFormatterImpl;
use app\common\infrastructure\service\TimeFormatterService;
use app\modules\v2\link\service\impl\StaticUrlDeliveryVolumeImpl;
use app\modules\v2\link\service\StaticUrlDeliveryVolumeService;
use app\modules\v2\marketDept\service\impl\PhysicalReplaceOrderImpl;
use app\modules\v2\marketDept\service\impl\PhysicalSendStatusImpl;
use app\modules\v2\marketDept\service\PhysicalReplaceOrderService;
use app\modules\v2\marketDept\service\PhysicalSendStatusService;
use app\modules\v2\operateDept\service\DesignCenterImageService;
use app\modules\v2\operateDept\service\DesignCenterImageStatisticsService;
use app\modules\v2\operateDept\service\DesignCenterProviderInfoService;
use app\modules\v2\operateDept\service\impl\DesignCenterImageImpl;
use app\modules\v2\operateDept\service\impl\DesignCenterImageStatisticsImpl;
use app\modules\v2\operateDept\service\impl\DesignCenterProviderInfoImpl;

$containerList = [

    //api接口
    OauthService::class            => OauthImpl::class,
    UacOauthService::class         => UacOauthImpl::class,
    UacUserService::class          => UacUserImpl::class,

    //领域服务
    StaticUrlDeliveryVolumeService::class       => StaticUrlDeliveryVolumeImpl::class,
    DesignCenterImageService::class             => DesignCenterImageImpl::class,
    DesignCenterImageStatisticsService::class   => DesignCenterImageStatisticsImpl::class,
    DesignCenterProviderInfoService::class      => DesignCenterProviderInfoImpl::class,
    PhysicalReplaceOrderService::class          => PhysicalReplaceOrderImpl::class,
    PhysicalSendStatusService::class            => PhysicalSendStatusImpl::class,

    //基础设施层
    ExcelService::class            => ExcelServiceImpl::class,
    DataSetCalculateService::class => DataSetCalculateImpl::class,
    TimeFormatterService::class    => TimeFormatterImpl::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}