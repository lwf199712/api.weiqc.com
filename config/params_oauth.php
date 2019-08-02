<?php

return [
    /**
     * 腾讯 oauth2.0 验证地址 及本地鉴权接收地址
     */
    'tencent_marketing_api' => [
        'token_key'    => 'tencent_marketing_api:',
        'user_actions' => [
            //应用id
            'client_id'     => 1108793948,
            //应用 secret
            'client_secret' => 'e94dc8b5d9c675e81fd3f34952404367',
            //重定向鉴权地址
            'authorize_url' => 'https://developers.e.qq.com/oauth/authorize',
            //鉴权跳转地址,本地接收
            'redirect_uri'  => YII_ENV === 'prod' ? 'http://apigdt.wqc.so/v1/oauth/authorize/token' : 'https://gdttest.wqc.so/v1/oauth/authorize/token',
            //token获取地址
            'token_url'     => 'https://api.e.qq.com/oauth/token'
        ]
    ],

    /**
     * 今日头条 oauth2.0 验证地址
     */
    'toutiao_marketing_api' => [
        'token_key' => 'toutiao_marketing_api',
        'app_id'    => 1111,
        //鉴权地址
        'authorize_url' => 'openapi/audit/oauth',
        //鉴权回调地址
        'redirect_uri' => 'xxx',
        //token获取地址
        'token_uri'  => ''
    ]
];