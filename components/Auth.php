<?php
declare(strict_types=1);

namespace app\components;

use mdm\admin\components\Helper;
use Yii;
use yii\base\Component;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class Auth extends Component
{
    /**
     *  跨域
     *
     * @param array $behaviors 已有的认证方法
     *
     * @return array
     * @author: wumahoo
     */
    public static function authentication(array $behaviors): array
    {
        //跨域
        $behaviors = self::Cross($behaviors);

        // 权限验证
        $behaviors = self::Authorization($behaviors);

        //设置响应的格式
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * CORS跨域处理
     *
     * @param array $behaviors
     * @return array
     * @author wumahoo
     */
    public static function Cross(array $behaviors): array
    {
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = array(
            'class' => Cors::class,
        );

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }


    /**
     * 授权登陆
     *
     * @param array $behaviors
     * @return array
     * @author wumahoo
     */
    public static function Authorization(array $behaviors): array
    {
        //验证规则
        $behaviors['authenticator'] = [
            'class'       => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
            ],
        ];

        //调试模式下可使用access-token接收参数
        if (YII_DEBUG) {
            //http://localhost/user/index/index?access-token=123
            $behaviors['authenticator']['authMethods'][] = QueryParamAuth::class;
        }

        return $behaviors;
    }

    /**
     * 检查用户是否拥有权限操作的api
     *
     * @param $actionId string 所在控制器的方法
     * @param $user string 已认证的用户
     * @return bool
     * @throws ForbiddenHttpException
     * @author: wumahoo
     */
    public static function checkRoute($actionId, $user): bool
    {
        if (Helper::checkRoute('/' . $actionId, Yii::$app->getRequest()->get(), $user)) {
            return true;
        }
        throw new ForbiddenHttpException();
    }


}