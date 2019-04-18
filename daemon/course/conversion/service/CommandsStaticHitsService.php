<?php

namespace app\daemon\course\conversion\service;


use app\common\exception\TencentMarketingApiException;
use yii\db\Exception;

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
     * @throws TencentMarketingApiException|Exception
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): void;
}
