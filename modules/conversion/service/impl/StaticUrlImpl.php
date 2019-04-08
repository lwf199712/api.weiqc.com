<?php

namespace app\modules\conversion\service\impl;

use app\modules\conversion\domain\StaticUrl;
use app\modules\conversion\service\StaticUrlService;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticUrlImpl implements StaticUrlService
{
    /* @var StaticUrl */
    private static $staticUrl = StaticUrl::class;

    /**
     * @param mixed $condition
     * @return StaticUrl|mixed|null
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticUrl::findOne($condition);
    }
}
