<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\RedisException;
use app\daemon\course\conversion\domain\dto\RedisAddViewDto;

/**
 * Interface UserActionStaticConversionService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface UserActionCache
{

    /**
     * 缓存用户行为 - 浏览(独立ip记录)
     *
     * @param RedisAddViewDto $redisAddViewDto
     * @return void
     * @throws RedisException
     * @author: lirong
     */
    public function addViews(RedisAddViewDto $redisAddViewDto): void;
}
