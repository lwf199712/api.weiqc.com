<?php

return [
    'tencent_marketing_api' => [
        'user_actions' => [
            //应用id
            'client_id'     => 1108793948,
            //应用 secret
            'client_secret' => 'IhhA6VLok2qBpLAq',
            //重定向鉴权地址
            'redirect_url'  => 'https://developers.e.qq.com/oauth/authorize',
            //TODO 鉴权跳转地址,本地接收(上线请修改域名)
            'redirect_uri'  => 'http://api.weiqc.com/v1/oauth/authorize/token',
            //token获取地址
            'token_url'     => 'https://api.e.qq.com/oauth/token'
        ]
    ]
];