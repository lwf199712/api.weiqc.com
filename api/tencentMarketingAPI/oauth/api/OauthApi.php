<?php

namespace app\api\tencentMarketingApi\oauth\api;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\api\tencentMarketingAPI\oauth\service\OauthService;
use app\common\api\ApiBaseController;
use app\common\exception\TencentMarketingApiException;
use app\modules\v1\oauth\domain\dto\AuthorizerTokenDto;

/**
 * 鉴权api
 * Class OauthApi
 *
 * @property OauthService oauthService
 * @package app\api\tencentMarketingApi\userActions\api
 * @author: lirong
 */
class OauthApi extends ApiBaseController
{
    /* @var OauthService */
    private $oauthService;

    /**
     * UserActionsAip constructor.
     *
     * @param OauthService $oauthService
     * @param array $config
     */
    public function __construct(OauthService $oauthService, $config = [])
    {
        $this->oauthService = $oauthService;
        parent::__construct($config);
    }

    /**
     * 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @param AuthorizerTokenDto $authorizationTokenDto
     * @return OauthDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function token(AuthorizerTokenDto $authorizationTokenDto): OauthDto
    {
        return $this->oauthService->token($authorizationTokenDto);
    }

}