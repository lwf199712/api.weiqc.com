<?php
$crossDomain = require __DIR__ . '/params_cross_domain.php';
$mobile = require  __DIR__ . '/params_mobile.php';
$api = require __DIR__ . '/params_api.php';
$redis = require __DIR__ . '/params_redis.php';
$oauth = require __DIR__ . '/params_oauth.php';
return [
    'adminEmail'   => 'admin@example.com',
    //允许跨域
    'cross_domain' => $crossDomain,
    //移动终端
    'mobile'       => $mobile,
    //api配置
    'api'          => $api,
    //redis配置
    'redis'        => $redis,
    //鉴权配置
    'oauth'        => $oauth,
];
