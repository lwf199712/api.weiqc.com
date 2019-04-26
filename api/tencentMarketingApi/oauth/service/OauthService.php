<?php

namespace app\api\tencentMarketingApi\oauth\service;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenRequestDto;
use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenResponseDto;
use app\common\exception\TencentMarketingApiException;

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
     * @param OauthTokenRequestDto $authorizationTokenDto
     * @return OauthTokenResponseDto
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function authorizeToken(OauthTokenRequestDto $authorizationTokenDto): OauthTokenResponseDto;
}
