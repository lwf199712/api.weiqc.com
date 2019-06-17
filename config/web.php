<?php
/**
 * web config
 *
 * @author lirong
 */

use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;
use yii\web\JsonParser;
use app\modules\v1\Module as V1Module;
use yii\web\Response;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use app\models\User;
use yii\caching\FileCache;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
//v1容器注册
require_once __DIR__ . '/container/v1_container.php';

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
            'class' => V1Module::class,
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
                $actionId = Yii::$app->controller->action->id;
                if ($response->data !== null && $event->sender->format === 'json' && !in_array($actionId, ['index', 'prepareDataProvider'], false)) {
                    $responseData = $response->data;
                    $message = array_shift($responseData);
                    $code = array_shift($responseData);
                    $data = array_shift($responseData);
                    $response->data = [
                        'message' => (string)$message,
                        'code'    => (int)$code,
                        'data'    => is_string($data) ? [$data] : $data,
                    ];
                    ksort($response->data);
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
        'class' => DebugModule::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;