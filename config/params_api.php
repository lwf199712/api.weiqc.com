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
     * 云片api地址
     */
    'yunpian_api'           => [
        'base_url'     => 'https://yunpian.com/v2/',
        'apikey'       => 'd0ae361514318bc4fc811186ed05afe7',
        'sms_actions' => [
            //单条短信发送
            'single_send_uri' => 'sms/single_send.json',
            //批量短信发送
            'batch_send_uri'  => 'sms/batch_send.json'
        ],
    ],
];