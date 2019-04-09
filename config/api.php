<?php
return [
    'tencent_marketing_api' => [
        //https://api.e.qq.com/v1.0/user_actions/add?access_token=<ACCESS_TOKEN>&timestamp=<TIMESTAMP>&nonce=<NONCE>
        //正式api
        'base_url' => YII_ENV === 'prod' ? 'https://api.e.qq.com/v1.1/' : 'https://sandbox-api.e.qq.com/v1.1/',
        '沙箱应用id',
        '关联代理商帐号',
        'token'    => '',
        'api'      => [
            //上传用户行为数据
            'user_actions/add'
        ]
    ]
];