<?php

namespace app\modules\v1\oauth\service;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;

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
     * @param OauthDto $oauthDto
     * @return void
     * @author: lirong
     */
    public function cacheToken(OauthDto $oauthDto): void;
}
