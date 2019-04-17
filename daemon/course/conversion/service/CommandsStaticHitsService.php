<?php

namespace app\daemon\course\conversion\service;


use app\common\exception\TencentMarketingApiException;

/**
 * Interface CommandsStaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface CommandsStaticHitsService
{
    /**
     * batch insert
     *
     * @param array $redisAddViewDtoList
     * @return void
     * @throws TencentMarketingApiException
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): void;
}
