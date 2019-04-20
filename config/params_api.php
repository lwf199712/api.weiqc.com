<?php
return [
    /**
     * 腾讯api地址
     */
    'tencent_marketing_api' => [
        'base_url'     => YII_ENV === 'prod' ? 'https://api.e.qq.com/v1.1/' : 'https://sandbox-api.e.qq.com/v1.1/',
        'access_token' => YII_ENV === 'prod' ? '635bcbdac7be8e86d5f99d95dd52b67a' : 'ce50f5a67bde88c617d901e95b597639',
        'api'          => [
            'user_actions' => [
                //上传用户行为数据
                'add' => 'user_actions/add'
            ],
        ]
    ]
];