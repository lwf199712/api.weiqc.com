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
            'client_secret' => 'IhhA6VLok2qBpLAq',
            //重定向鉴权地址
            'authorize_url' => 'https://developers.e.qq.com/oauth/authorize',
            //鉴权跳转地址,本地接收
            'redirect_uri'  => YII_ENV === 'prod' ? 'http://gdttest.wqc.so/v1/oauth/authorize/token' : 'http://api.weiqc.com/v1/oauth/authorize/token',
            //token获取地址
            'token_url'     => 'https://api.e.qq.com/oauth/token'
        ]
    ]
];
