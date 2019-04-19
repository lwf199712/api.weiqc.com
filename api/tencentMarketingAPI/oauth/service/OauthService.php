<?php

namespace app\api\tencentMarketingAPI\oauth\service;

use app\modules\v1\oauth\domain\vo\AuthorizationTokenDto;

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
     * @param AuthorizationTokenDto $authorizationTokenDto
     * @author: lirong
     */
    public function token(AuthorizationTokenDto $authorizationTokenDto): void;
}
