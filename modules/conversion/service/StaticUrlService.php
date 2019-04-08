<?php

namespace app\modules\conversion\service;

use app\modules\conversion\domain\StaticUrl;

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
