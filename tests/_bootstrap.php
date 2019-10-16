<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ .'/../vendor/autoload.php';

//v1容器注册
require_once __DIR__ . '/../config/container/v1_container.php';
//v2容器注册
require_once __DIR__ . '/../config/container/v2_container.php';
