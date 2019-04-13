<?php

namespace app\modules\v1\userAction\service\impl;

use app\modules\v1\userAction\domain\po\StaticUrlPo;
use app\modules\v1\userAction\service\StaticUrlService;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticUrlImpl implements StaticUrlService
{
    /* @var StaticUrlPo */
    private static $staticUrl = StaticUrlPo::class;

    /**
     * @param mixed $condition
     * @return StaticUrlPo|mixed|null
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticUrl::findOne($condition);
    }
}
