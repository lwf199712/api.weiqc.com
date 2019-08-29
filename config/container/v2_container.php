<?php declare(strict_types=1);

/**
 * 容器注册(只为v2代码服务)
 *
 * @author zhuozhen
 */

use app\api\toutiaoMarketingApi\oauth\service\impl\OauthImpl;
use app\common\infrastructure\service\DataSetCalculateService;
use app\common\infrastructure\service\ExcelService;
use app\common\infrastructure\service\impl\DataSetCalculateImpl;
use app\common\infrastructure\service\impl\ExcelServiceImpl;
use \app\api\toutiaoMarketingApi\oauth\service\Oauth as OauthService;
use app\common\infrastructure\service\impl\TimeFormatterImpl;
use app\common\infrastructure\service\TimeFormatterService;
use app\modules\v2\link\service\impl\StaticUrlDeliveryVolumeImpl;
use app\modules\v2\link\service\StaticUrlDeliveryVolumeService;

$containerList = [

    //api接口
    OauthService::class            => OauthImpl::class,

    //领域服务
    StaticUrlDeliveryVolumeService::class => StaticUrlDeliveryVolumeImpl::class,

    //基础设施层
    ExcelService::class            => ExcelServiceImpl::class,
    DataSetCalculateService::class => DataSetCalculateImpl::class,
    TimeFormatterService::class    => TimeFormatterImpl::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}