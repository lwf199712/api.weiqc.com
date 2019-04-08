<?php

namespace app\modules\v1;

use yii\base\Module as BaseModule;
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
     * the namespace that controller classes are in.
     *
     * @var string $controllerNamespace
     * @author: lirong
     */
    public $controllerNamespace = 'app\modules\v1\rest';

    /**
     * Initializes the object.
     *
     * @return mixed|void
     * @author: lirong
     */
    public function init()
    {
        parent::init();
        //禁用session
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;
    }
}
