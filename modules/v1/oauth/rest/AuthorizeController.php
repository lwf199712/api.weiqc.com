<?php

namespace app\modules\v1\oauth\rest;

use app\common\rest\RestBaseController;
use Yii;

/**
 * 鉴权控制器
 * Class AuthorizeController
 *
 * @package app\modules\v1\oauth\rest
 * @author: lirong
 */
class AuthorizeController extends RestBaseController
{
    public function transactionClose(): array
    {
        return ['actionAuthorize'];
    }

    /**
     * 鉴权 - 获取 Authorization Code
     *
     * @author: lirong
     */
    public function actionUserActions(): void
    {
        $this->redirect(Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['redirect_uri'], [

        ]);
    }
}