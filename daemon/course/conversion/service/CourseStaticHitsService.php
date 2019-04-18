<?php

namespace app\daemon\course\conversion\service;


use yii\db\Exception;

/**
 * Interface CommandsStaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface CourseStaticHitsService
{
    /**
     * batch insert
     *
     * @param array $redisAddViewDtoList
     * @return array
     * @throws Exception
     * @author: lirong
     */
    public function batchInsert(array $redisAddViewDtoList): array;
}
