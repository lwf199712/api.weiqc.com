<?php

namespace app\modules\v1\service\impl;

use app\modules\v1\domain\StaticUrl;
use app\modules\v1\service\StaticUrlService;

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
