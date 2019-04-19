<?php

namespace app\modules\v1;

use yii\base\Module as BaseModule;
use app\modules\v1\userAction\Module as ConversionModule;
use app\modules\v1\oauth\Module as OauthModule;
use Yii;

/**
 * Module module definition class
 * Class Module
 *
 * @package app\modules\v1
 * @author: lirong
 */
class Module extends BaseModule
{
    /**
     * Initializes the object.
     *
     * @return mixed|void
     * @author: lirong
     */
    public function init()
    {
        parent::init();
        //Disable session
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;

        $this->modules = [
            //上报行为
            'user-action' => [
                'class' => ConversionModule::class,
            ],
            //鉴权
            'oauth'       => [
                'class' => OauthModule::class,
            ]
        ];
    }
}
