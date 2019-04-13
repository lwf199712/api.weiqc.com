<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticConversionPo;

/**
 * Interface StaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface StaticHitsService
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
