<?php
declare(strict_types=1);

namespace app\common\rest;


use app\components\Auth;
use Yii;
use yii\web\ForbiddenHttpException;

class AdminBaseController extends RestBaseController
{
    /**
     * v2:重写行为
     * @return array
     * @author zhuozhen
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return Auth::authentication($behaviors);
    }

    /**
     * v2:API授权认证
     * @param string $action
     * @param null   $model
     * @param array  $params
     * @return bool|void
     * @throws ForbiddenHttpException
     * @author zhuozhen
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return Auth::checkRoute($this->getUniqueId() . '/' . $action, Yii::$app->user);
    }
}