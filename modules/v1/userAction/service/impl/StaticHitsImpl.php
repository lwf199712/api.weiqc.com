<?php

namespace app\modules\v1\userAction\service\impl;

use app\modules\v1\userAction\domain\po\StaticHitsPo;
use app\modules\v1\userAction\service\StaticHitsService;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticHitsImpl implements StaticHitsService
{
    /* @var StaticHitsPo */
    private static $staticHits = StaticHitsPo::class;

    /**
     * @param mixed $condition
     * @return StaticHitsPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticHits::findOne($condition);
    }
}
