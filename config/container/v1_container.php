<?php

/**
 * 容器注册(只为v1代码服务)
 *
 * @author lirong
 */

use app\modules\v1\userAction\service\impl\StaticConversionImpl;
use app\modules\v1\userAction\service\impl\StaticHitsImpl;
use app\modules\v1\userAction\service\impl\StaticServiceConversionsImpl;
use app\modules\v1\userAction\service\impl\StaticUrlImpl;
use app\modules\v1\userAction\service\StaticConversionService;
use app\modules\v1\userAction\service\StaticHitsService;
use app\modules\v1\userAction\service\StaticServiceConversionsService;
use app\modules\v1\userAction\service\StaticUrlService;

$containerList = [
    StaticUrlService::class                => StaticUrlImpl::class,
    StaticConversionService::class         => StaticConversionImpl::class,
    StaticServiceConversionsService::class => StaticServiceConversionsImpl::class,
    StaticHitsService::class               => StaticHitsImpl::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
