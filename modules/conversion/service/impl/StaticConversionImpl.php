<?php

namespace app\modules\conversion\service\impl;

use app\exception\ValidateException;
use app\modules\conversion\domain\StaticConversion;
use app\modules\conversion\service\StaticConversionService;

/**
 * Interface ConversionService
 *
 * @author: lirong
 */
class StaticConversionImpl implements StaticConversionService
{
    /* @var StaticConversion */
    private static $staticConversion = StaticConversion::class;

    /**
     * @param mixed $condition
     * @return StaticConversion|null|mixed
     * @author: lirong
     */
    public static function findOne($condition)
    {
        return self::$staticConversion::findOne($condition);
    }

    /**
     * @param StaticConversion $staticConversion
     * @return void
     * @throws ValidateException
     * @author: lirong
     */
    public static function insert($staticConversion): void
    {
        if (!$staticConversion->save()) {
            throw new ValidateException($staticConversion, '表单参数校验异常！', 302);
        }
    }
}
