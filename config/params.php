<?php
$crossDomain = require __DIR__ . '/cross_domain.php';
$tencentMarketingApi = require __DIR__ . '/tencent_marketing_api.php';
return [
    'adminEmail'            => 'admin@example.com',
    'cross_domain'          => $crossDomain,
    'tencent_marketing_api' => $tencentMarketingApi
];
