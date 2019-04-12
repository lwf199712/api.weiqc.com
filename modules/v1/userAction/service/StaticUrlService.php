<?php

namespace app\modules\v1\userAction\service;

use app\modules\v1\userAction\domain\po\StaticUrl;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
interface StaticUrlService
{
    /**
     * @param mixed $condition
     * @return StaticUrl|null|mixed
     * @author: lirong
     */
    public static function findOne($condition);
}
