<?php

/**
 * web config
 *
 * @author lirong
 */

use app\common\rest\RestBaseController;
use mdm\admin\components\AccessControl;
use mdm\admin\Module;
use yii\rbac\DbManager;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;
use yii\web\JsonParser;
use app\modules\v1\Module as V1Module;
use app\modules\v2\Module as V2Module;
use yii\web\Response;
use yii\log\FileTarget;
use yii\swiftmailer\Mailer;
use app\models\User;
use yii\caching\FileCache;

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';
//v1容器注册
require_once __DIR__ . '/container/v1_container.php';

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'language'   => 'zh-CN',
    'bootstrap'  => ['log'],
    'aliases'    => [
        '@bower'     => '@vendor/bower-asset',
        '@npm'       => '@vendor/npm-asset',
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'modules'    => [
        //load conversion modules
        'v1'    => [
            'class' => V1Module::class,
        ],
        'v2'    => [
            'class' => V2Module::class,
        ],
        //加载RBAC权限管理模块
        'admin' => [
            'class'      => Module::class,
            'layout'     => 'right-menu',
            'mainLayout' => '@app/views/layouts/main.php',
            'menus'      => [
                'user/signup' => [
                    'label' => '新增用户',
                ],
            ],
        ],

    ],
    'components' => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'cQVUlOD_Ex9tcIfnLkdCloLB8CjRLDodLDOd',
            'parsers'             => [
                //receive form-data to receive json data
                'application/json' => JsonParser::class,
            ],
        ],
        'response'     => [
            'class'         => Response::class,
            'on beforeSend' => static function ($event) {
                $response = $event->sender;
                if ($event->sender->statusCode !== 500 && Yii::$app->controller instanceof  RestBaseController
                ) {
                    if ($response->data !== null && $event->sender->format === 'json') {
                        $responseData   = $response->data;
                        $message        = array_shift($responseData);
                        $code           = array_shift($responseData);
                        $data           = array_shift($responseData);
                        $response->data = [
                            'message' => (string)$message,
                            'code'    => (int)$code,
                            'data'    => is_string($data) ? [$data] : $data,
                        ];
                        ksort($response->data);
                    }
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
                    'levels' => ['error', 'warning', 'info', 'trace'],
                ],
                //TODO 自定义info日志,用于记录post参数(线上调试用,调试完请删除)
                [
                    'class'       => FileTarget::class,
                    'levels'      => ['info'],
                    'categories'  => ['post_params'],
                    'logFile'     => '@app/runtime/logs/post_params.log',
                    'logVars'     => ['*'],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                //TODO 自定义info日志,用于记录api参数(线上调试用,调试完请删除)
                [
                    'class'       => FileTarget::class,
                    'levels'      => ['info'],
                    'categories'  => ['api_params'],
                    'logFile'     => '@app/runtime/logs/api_params.log',
                    'logVars'     => ['*'],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                //TODO 自定义info日志,用于记录api参数响应值
                [
                    'class'       => FileTarget::class,
                    'levels'      => ['info'],
                    'categories'  => ['api_response'],
                    'logFile'     => '@app/runtime/logs/api_response.log',
                    'logVars'     => ['*'],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        //路由美化
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require __DIR__ . '/route.php',
        ],
        'authManager'  => [
            'class' => DbManager::class,
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
        ],
        'db'           => $db,
    ],

    'as access' => [
        'class'        => AccessControl::class,
        'allowActions' => [
            'site/*',
            'admin/*',
            'v1/*',
            'gii/*',
            'debug/*',
            'v2/*',
            //此处的action列表，允许任何人（包括游客）访问
            //所以如果是正式环境（线上环境），不应该在这里配置任何东西，为空即可
            //但是为了在开发环境更简单的使用，可以在此处配置你所需要的任何权限
            //在开发完成之后，需要清空这里的配置，转而在系统里面通过RBAC配置权限
        ],
    ],
    'params'    => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class' => DebugModule::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;