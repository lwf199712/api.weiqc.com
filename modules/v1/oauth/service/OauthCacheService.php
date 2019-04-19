<?php

namespace app\modules\v1\oauth\service;

use app\modules\v1\oauth\domain\vo\AuthorizationTokenDto;

/**
 * Interface UserActionStaticConversionService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface OauthCacheService
{

    /**
     * 缓存 - 缓存token
     *
     * @param AuthorizationTokenDto $authorizationTokenDto
     * @return void
     * @author: lirong
     */
    public function cacheToken(AuthorizationTokenDto $authorizationTokenDto): void;
}
