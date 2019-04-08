<?php
$crossDomain = require __DIR__ . '/cross_domain.php';
$api = require __DIR__ . '/api.php';
return [
    'adminEmail'   => 'admin@example.com',
    'cross_domain' => $crossDomain,
    'api'          => $api
];
