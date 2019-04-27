<?php
/**
 * commands config
 *
 * @author lirong
 */

use yii\gii\Module;
use yii\log\FileTarget;
use yii\caching\FileCache;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id'                  => 'basic-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components'          => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'log'   => [
            'targets' => [
                [
                    'class'  => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'       => FileTarget::class,
                    'levels'      => ['error'],
                    'categories'  => ['daemon'],
                    'logFile'     => '@app/runtime/logs/daemon.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'db'    => $db,
    ],
    'params'              => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => Module::class,
    ];
}

return $config;
