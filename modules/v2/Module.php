<?php
declare(strict_types=1);
namespace app\modules\v2;

use yii\base\Module as BaseModule;
use app\modules\v2\oauth\Module as OauthModule;
use app\modules\v2\link\Module as LinkModule;
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
            //鉴权
            'oauth'       => [
                'class' => OauthModule::class,
            ],
            'link'       => [
                'class' => LinkModule::class,
            ]
        ];
    }
}
