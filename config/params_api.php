<?php


return [
    /**
     * 腾讯api地址
     */
    'tencent_marketing_api' => [
        'base_url' => YII_ENV === 'prod' ? 'https://api.e.qq.com/v1.1/' : 'https://sandbox-api.e.qq.com/v1.1/',
        'api'      => [
            'user_actions'     => [
                //上传用户行为数据
                'add' => 'user_actions/add',
            ],
            'user_action_sets' => [
                //创建用户行为数据源
                'add' => 'user_action_sets/add',
            ],
        ],
    ],


    /**
     * 头条api地址
     */
    'toutiao_marketing_api' => [
        'base_url' => 'http://ad.toutiao.com/',
        'api'      => [
            'access_token'  => 'open_api/oauth2/access_token',
            'refresh_token' => 'open_api/oauth2/refresh_token',
        ],
    ],

    /**
     * UAC－api地址
     */
    'uac_api'               => [
        'base_url'      => '10.29.191.229:18206',
        'client_id'     => '91052cff6f461272a7c298079a235e84',
        'client_secret' => '1b64f0e6c9ae1e80c8282b5e6d3d8a92',
        'api'           => [
            'access_token'  => 'authentication/password-grant',
            'refresh_token' => '/authentication/refresh-token',
            'user'          => 'auth/userinfo',
        ],
    ],


    /**
     * 云片api地址
     */
    'yunpian_api'           => [
        'base_url'    => 'https://yunpian.com/v2/',
        'apikey'      => 'd0ae361514318bc4fc811186ed05afe7',
        'sms_actions' => [
            //单条短信发送
            'single_send_uri' => 'sms/single_send.json',
            //批量短信发送
            'batch_send_uri'  => 'sms/batch_send.json',
        ],
    ],
];