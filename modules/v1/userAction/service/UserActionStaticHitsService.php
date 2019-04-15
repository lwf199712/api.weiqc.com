<?php

namespace app\modules\v1\userAction\service;

use app\models\po\StaticConversionPo;

/**
 * Interface CommandsStaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface UserActionStaticHitsService
{
    /**
     * @param mixed $condition
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public function findOne($condition);

    /**
     * @param mixed $condition
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public function exists($condition);
}
