<?php

use yii\web\JsonParser;
use app\modules\v1\Module;
use yii\web\Response;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use app\models\User;
use yii\caching\FileCache;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'    => [
        //load conversion modules
        'v1' => [
            'class' => Module::class,
        ]
    ],
    'components' => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'cQVUlOD_Ex9tcIfnLkdCloLB8CjRLDodLDOd',
            'parsers'             => [
                //receive form-data to receive json data
                'application/json' => JsonParser::class,
            ]
        ],
        'response'     => [
            'class'         => Response::class,
            'on beforeSend' => static function ($event) {
                $response = $event->sender;
                if ($response->data !== null && $event->sender->format === 'json') {
                    $data = array_values($response->data);
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data'    => [
                            'status'  => array_shift($data),
                            'message' => array_shift($data),
                            'code'    => array_shift($data),
                            'data'    => array_shift($data)
                        ],
                    ];
                    $response->statusCode = 200;
                }
            },
        ],
        'cache'        => [
            'class' => FileCache::class,
        ],
        'user'         => [
            'identityClass'   => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        //设置format 的默认时区
        'formatter'    => [
            'defaultTimeZone' => 'Asia/Shanghai',
            'locale'          => 'zh-CN',
            'dateFormat'      => 'yyyy-MM-dd',
            'datetimeFormat'  => 'yyyy-MM-dd HH:mm:ss',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        //路由美化
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require __DIR__ . '/route.php',
        ],
        'db'           => $db,
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
