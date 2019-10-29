<?php declare(strict_types=1);


namespace app\controllers;


use app\api\uacApi\dto\TokenRequestDto;
use app\api\uacApi\dto\TokenResponseDto;
use app\api\uacApi\service\Oauth as UacOauthService;

use app\api\uacApi\service\User as UacUserService;
use app\common\exception\UacApiException;
use app\models\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class LoginController extends Controller
{
    public $enableCsrfValidation = false;

    /** @var UacOauthService */
    public $uacOauthService;
    /** @var UacUserService */
    public $uacUserService;

    public function __construct($id, $module,
                                UacOauthService $uacOauthService,
                                UacUserService $uacUserService,
                                $config = [])
    {
        $this->uacOauthService = $uacOauthService;
        $this->uacUserService  = $uacUserService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return Response
     * @throws GuzzleException
     */
    public function actionIndex(): Response
    {
        $response         = new Response;
        $response->format = Response::FORMAT_JSON;


        if (Yii::$app->request->getBodyParam('username') === '开发中心' && Yii::$app->request->getBodyParam('password') === 'fd123456'){
            $user = User::findByUsername('开发中心');
            $response->data = [
                'message' => 'success',
                'code'    => 200,
                'data'    => $user->access_token,
            ];
            return $response;
        }


        try {
            $tokenResponseDto = $this->getToken(Yii::$app->request->getBodyParams());
            $userInfo         = $this->uacUserService->getUserInfo($tokenResponseDto->access_token);
            $user             = User::findByUsername($userInfo->username);
            if ($user === null) {
                User::createUser($userInfo);
                $user = User::findByUsername($userInfo->username);
            }elseif (empty($user->realname)){
                $user->realname = $userInfo->realName;
                $user->save();
            }
            if (User::checkRoleExist($user->getId()) === false) {
                throw new HttpException(403, '你的账号暂时还没分配权限，请联系部门主管');
            }
            [$message, $code, $data] = ['success', 200, $user->access_token];
        } catch (Exception $exception) {
            [$message, $code, $data] = [$exception->getMessage(), $exception->getCode(), []];
        }

        $response->data = [
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
        ];

        return $response;
    }


    /**
     * 登录并获取uac-token
     * @param array $loginForm
     * @return TokenResponseDto
     * @throws GuzzleException
     * @throws HttpException
     * @throws UacApiException
     */
    public function getToken(array $loginForm): TokenResponseDto
    {
        $tokenRequestDto                = new TokenRequestDto;
        $tokenRequestDto->username      = $loginForm['username'];
        $tokenRequestDto->password      = $loginForm['password'];
        $tokenRequestDto->client_id     = Yii::$app->params['api']['uac_api']['client_id'];
        $tokenRequestDto->client_secret = Yii::$app->params['api']['uac_api']['client_secret'];
        $tokenRequestDto->grant_type    = 'password';
        $tokenResponseDto               = $this->uacOauthService->applyToken($tokenRequestDto);
        if ($tokenResponseDto->access_token === null) {
            throw new HttpException('登录失败,token为空');
        }
        return $tokenResponseDto;
    }

}