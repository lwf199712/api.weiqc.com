<?php

namespace app\modules\v1\oauth\service;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthDto;
use app\common\exception\RedisException;
use Predis\Connection\ConnectionException;

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
     * @return OauthDto
     * @throws RedisException|ConnectionException
     * @author: lirong
     */
    public function cacheToken(OauthDto $oauthDto): OauthDto;
}
