<?php

use yii\rest\UrlRule;

/**
 * restful 路由规则匹配
 *
 * @author lirong
 * @data: 2019-04-02
 * @version 1.0
 */
return [
    [
        'class'         => UrlRule::class,
        'controller'    => [
            'v1/conversion/rest',
        ],
        //统一处理OPTIONS请求
        'extraPatterns' => [
            'OPTIONS <action:.*>' => 'options',
        ],
        //禁用末尾采用复数的形式
        'pluralize'     => false,
    ],
];
