<?php
/**
 * 容器注册(只为v2代码服务)
 *
 * @author zhuozhen
 */

use app\common\infrastructure\service\ExcelService;
use app\common\infrastructure\service\impl\ExcelServiceImpl;

$containerList = [
    ExcelService::class => ExcelServiceImpl::class
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}