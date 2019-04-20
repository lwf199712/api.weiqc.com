<?php

namespace app\modules\v1\oauth\rest;

use app\api\tencentMarketingApi\oauth\api\OauthApi;
use app\common\exception\RedisException;
use app\common\exception\TencentMarketingApiException;
use app\common\utils\UrlUtils;
use app\common\web\WebBaseController;
use app\modules\v1\oauth\domain\dto\AuthorizerTokenDto;
use app\modules\v1\oauth\domain\vo\AuthorizeRequestVo;
use app\modules\v1\oauth\domain\vo\AuthorizeResponseVo;
use app\modules\v1\oauth\enum\AuthorizationTokenEnum;
use app\modules\v1\oauth\enum\AuthorizeEnum;
use app\modules\v1\oauth\service\OauthCacheService;
use Predis\Connection\ConnectionException;
use Yii;
use yii\db\Exception;

/**
 * 鉴权控制器
 * Class AuthorizeController
 *
 * @property UrlUtils $urlUtils
 * @property OauthCacheService actionCache
 * @property OauthApi $oauthApi
 * @package app\modules\v1\oauth\rest
 * @author: lirong
 */
class AuthorizeController extends WebBaseController
{
    /* @var UrlUtils $urlUtils */
    public $urlUtils;
    /* @var OauthCacheService $actionCache */
    public $actionCache;
    /* @var OauthApi $oauthApi */
    public $oauthApi;

    /**
     * AuthorizeController constructor.
     *
     * @param $id
     * @param $module
     * @param UrlUtils $urlUtils
     * @param OauthCacheService $actionCacheService
     * @param OauthApi $oauthApi
     * @param array $config
     */
    public function __construct($id, $module,
                                UrlUtils $urlUtils,
                                OauthCacheService $actionCacheService,
                                OauthApi $oauthApi,
                                $config = [])
    {
        $this->urlUtils = $urlUtils;
        $this->actionCache = $actionCacheService;
        $this->oauthApi = $oauthApi;
        parent::__construct($id, $module, $config);
    }

    /**
     * transaction close
     *
     * @return array
     * @author: lirong
     */
    public function transactionClose(): array
    {
        return ['actionAuthorize', 'actionToken'];
    }

    /**
     * Declares the allowed HTTP verbs.
     *
     * @return array
     * @author: lirong
     */
    public function verbs(): array
    {
        return [
            'code-user-actions' => ['GET', 'HEAD'],
            'token'             => ['GET', 'HEAD']
        ];
    }

    /**
     * 鉴权 - 获取 Authorization Code
     *
     * @author: lirong
     */
    public function actionCodeUserActions(): void
    {
        $authorizeDto = new AuthorizeRequestVo();
        $authorizeDto->client_id = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['client_id'];
        $authorizeDto->redirect_uri = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['redirect_uri'];
        //TODO 用于验证
        $authorizeDto->state = '';
        $authorizeDto->scope = AuthorizeEnum::USER_ACTIONS;
        //重定向到腾讯页
        $this->redirect(Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['authorize_url'] .
            $this->urlUtils->getRequestParamsFromGet($authorizeDto->attributes))->send();
    }

    /**
     * 鉴权 - 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @return mixed
     * @author: lirong
     */
    public function actionToken()
    {
        try {
            $tokenDto = new AuthorizeResponseVo();
            $tokenDto->authorization_code = $this->request->get('authorization_code');
            //TODO 用于验证
            $tokenDto->state = $this->request->get('state');

            $authorizationTokenDto = new AuthorizerTokenDto();
            $authorizationTokenDto->client_id = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['client_id'];
            $authorizationTokenDto->client_secret = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['client_secret'];
            $authorizationTokenDto->grant_type = AuthorizationTokenEnum::AUTHORIZATION_CODE;
            $authorizationTokenDto->authorization_code = $tokenDto->authorization_code;
            $authorizationTokenDto->redirect_uri = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['redirect_uri'];//回调地址
            $oauthDto = $this->actionCache->cacheToken($this->oauthApi->token($authorizationTokenDto));
            return $this->render('@app/views/v1/oauth/token', ['oauthDto' => $oauthDto]);
        } catch (TencentMarketingApiException|RedisException|ConnectionException $e) {
            return $this->render('@app/views/site/error', ['message' => $e->getMessage(), 'name' => 'token获取失败!']);
        }
    }
}