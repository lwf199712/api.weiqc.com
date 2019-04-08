<?php

namespace app\modules\v1\service;

use app\modules\v1\domain\StaticUrl;

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
