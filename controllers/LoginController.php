<?php declare(strict_types=1);


namespace app\controllers;


use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class LoginController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return Response
     */
    public function actionIndex() :Response
    {
        $model = new LoginForm();
        $response = new Response();
        $response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            /** @var User $user */
            $user =  $model->getUser();
            $response->data = [
                'message' => '登录成功',
                'code'    => 200,
                'data'    => $user->access_token,
            ];
        }else{
            $response->data = [
                'message' => '登录失败',
                'code'    => 401,
                'data'    => '',
            ];
        }
        return  $response;

    }

}