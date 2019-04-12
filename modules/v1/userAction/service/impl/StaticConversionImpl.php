<?php

namespace app\modules\v1\userAction\service\impl;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticConversionPo;
use app\modules\v1\userAction\service\StaticConversionService;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticConversionImpl implements StaticConversionService
{
    /* @var StaticConversionPo */
    private static $staticConversion = StaticConversionPo::class;

    /**
     * @param mixed $condition
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticConversion::findOne($condition);
    }

    /**
     * @param StaticConversionPo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public static function insert($staticConversionPo): int
    {
        /* @var $staticConversion StaticConversionPo */
        $staticConversion = new self::$staticConversion;
        $staticConversion->attributes = $staticConversionPo->attributes;
        if (!$staticConversion->save()) {
            throw new ValidateException($staticConversion, '表单参数校验异常！', 302);
        }
        return (int)$staticConversion->id;
    }
}
