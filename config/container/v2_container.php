<?php
/**
 * 容器注册(只为v2代码服务)
 *
 * @author zhuozhen
 */

$containerList = [

];

foreach ($containerList as $class => $definition) {
    Yii::$container->set($class, $definition);
}