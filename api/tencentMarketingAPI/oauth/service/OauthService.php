<?php

namespace app\api\tencentMarketingAPI\oauth\service;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\common\exception\TencentMarketingApiException;
use app\modules\v1\oauth\domain\dto\AuthorizerTokenDto;

/**
 * Interface OauthTokenService
 *
 * @package app\modules\v1\oauth\service
 * @author: lirong
 */
interface OauthService
{

    /**
     * 通过 Authorization Code 获取 Access Token 或刷新 Access Token
     *
     * @param AuthorizerTokenDto $authorizationTokenDto
     * @throws TencentMarketingApiException
     * @return OauthDto
     * @author: lirong
     */
    public function token(AuthorizerTokenDto $authorizationTokenDto): OauthDto;
}
