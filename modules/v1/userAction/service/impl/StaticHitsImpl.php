<?php

namespace app\modules\v1\userAction\service\impl;

use app\modules\v1\userAction\domain\po\StaticConversionPo;
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
     * ActiveRecord instance matching the condition, or `null` if nothing matches.
     *
     * @param mixed $condition
     * @return StaticHitsPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticHits::findOne($condition);
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     *
     * @param mixed $condition
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public static function exists($condition): bool
    {
        return self::$staticHits::find()->where($condition)->exists();
    }
}
