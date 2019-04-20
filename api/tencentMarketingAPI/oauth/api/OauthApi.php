<?php

namespace app\api\tencentMarketingApi\oauth\api;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\api\tencentMarketingApi\oauth\service\OauthCacheService;
use app\api\tencentMarketingAPI\oauth\service\OauthService;
use app\common\api\ApiBaseController;
use app\common\exception\RedisException;
use app\common\exception\TencentMarketingApiException;
use app\modules\v1\oauth\domain\dto\AuthorizerTokenDto;
use app\modules\v1\oauth\enum\AuthorizationTokenEnum;
use Predis\Connection\ConnectionException;
use Yii;
use yii\db\Exception;

/**
 * 鉴权api
 * Class OauthApi
 *
 * @property OauthService oauthService
 * @property OauthCacheService $oauthCacheService
 * @package app\api\tencentMarketingApi\userActions\api
 * @author: lirong
 */
class OauthApi extends ApiBaseController
{
    /* @var OauthService */
    private $oauthService;
    /* @var OauthCacheService */
    private $oauthCacheService;

    /**
     * UserActionsAip constructor.
     *
     * @param OauthService $oauthService
     * @param OauthCacheService $oauthCacheService
     * @param array $config
     */
    public function __construct(OauthService $oauthService, OauthCacheService $oauthCacheService, $config = [])
    {
        $this->oauthService = $oauthService;
        $this->oauthCacheService = $oauthCacheService;
        parent::__construct($config);
    }

    /**
     * 鉴权api - 通过绑定的推广帐号id获得token
     *
     * @param $accountId
     * @return string
     * @throws TencentMarketingApiException
     * @throws Exception
     * @author: lirong
     */
    public function getToken(int $accountId): string
    {
        $oauthDto = $this->oauthCacheService->getToken($accountId);
        if (!$oauthDto) {
            $authorizationTokenDto = new AuthorizerTokenDto();
            $authorizationTokenDto->client_id = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['client_id'];
            $authorizationTokenDto->client_secret = Yii::$app->params['oauth']['tencent_marketing_api']['user_actions']['client_secret'];
            $authorizationTokenDto->grant_type = AuthorizationTokenEnum::REFRESH_TOKEN;
            $authorizationTokenDto->refresh_token = $oauthDto->refresh_token;
            //刷新token
            if (!$this->oauthService->authorizeToken($authorizationTokenDto)) {
                throw new Exception('刷新token失败!', [], 500);
            }
        }
        if (!$oauthDto) {
            throw new Exception('请重新鉴权!', [], 500);
        }
        return $oauthDto->access_token;
    }

    /**
     * 鉴权api - 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @param AuthorizerTokenDto $authorizationTokenDto
     * @return OauthDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function authorizeToken(AuthorizerTokenDto $authorizationTokenDto): OauthDto
    {
        return $this->oauthService->authorizeToken($authorizationTokenDto);
    }

    /**
     * 鉴权api - 缓存token至本地系统
     *
     * @param OauthDto $oauthDto
     * @return void
     * @throws ConnectionException
     * @throws RedisException
     * @author: lirong
     */
    public function cacheToken(OauthDto $oauthDto): void
    {
        $this->oauthCacheService->cacheToken($oauthDto);
    }

}