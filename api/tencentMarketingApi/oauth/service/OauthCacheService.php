<?php

namespace app\api\tencentMarketingApi\oauth\service;

use app\api\tencentMarketingApi\oauth\domain\dto\OauthTokenResponseDto;
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
     * @param OauthTokenResponseDto $oauthDto
     * @return void
     * @throws RedisException|ConnectionException
     * @author: lirong
     */
    public function cacheToken(OauthTokenResponseDto $oauthDto): void;

    /**
     * 缓存 - 获得token
     *
     * @param int $accountId
     * @return OauthTokenResponseDto
     * @author: lirong
     */
    public function getToken(int $accountId): ?OauthTokenResponseDto;
}
