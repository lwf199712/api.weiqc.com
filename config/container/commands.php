<?php

use app\commands\conversionCommands\service\CommandsStaticHitsService;
use app\commands\conversionCommands\service\impl\CommandsCommandsStaticHitsImpl;
use app\utils\ArrayUtils;
use app\utils\BatchInsertUtils;
use app\utils\RedisUtils;

$containerList = [
    //接口容器
    CommandsStaticHitsService::class => CommandsCommandsStaticHitsImpl::class,
    //工具
    ArrayUtils::class                => ArrayUtils::class,
    RedisUtils::class                => RedisUtils::class,
    BatchInsertUtils::class          => BatchInsertUtils::class,
];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}
