<?php

namespace app\commands\conversionCommands\service;


use app\commands\conversionCommands\domain\dto\RedisAddViewDto;

/**
 * Interface CommandsStaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface CommandsStaticHitsService
{
    /**
     * @param RedisAddViewDto $redisAddViewDto
     * @return mixed
     * @author: lirong
     */
    public function insert(RedisAddViewDto $redisAddViewDto): void;
}
