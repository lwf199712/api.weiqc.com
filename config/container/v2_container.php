<?php
/**
 * 容器注册(只为v2代码服务)
 *
 * @author zhuozhen
 */

use app\api\toutiaoMarketingApi\oauth\service\impl\OauthImpl;
use app\common\infrastructure\service\ExcelService;
use app\common\infrastructure\service\impl\ExcelServiceImpl;
use \app\api\toutiaoMarketingApi\oauth\service\Oauth as OauthService;

$containerList = [

    //api接口
    OauthService::class => OauthImpl::class,

    //基础设施层
    ExcelService::class => ExcelServiceImpl::class,

];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}