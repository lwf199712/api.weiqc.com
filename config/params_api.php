<?php
return [
    /**
     * 腾讯api地址
     */
    'tencent_marketing_api' => [
        'base_url'     => YII_ENV === 'prod' ? 'https://api.e.qq.com/v1.1/' : 'https://sandbox-api.e.qq.com/v1.1/',
        'api'          => [
            'user_actions' => [
                //上传用户行为数据
                'add' => 'user_actions/add'
            ],
        ]
    ]
];