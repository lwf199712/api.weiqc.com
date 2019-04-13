<?php

namespace app\modules\v1\userAction\service;

use app\modules\v1\userAction\domain\po\StaticUrlPo;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
interface StaticUrlService
{
    /**
     * @param mixed $condition
     * @return StaticUrlPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition);
}
