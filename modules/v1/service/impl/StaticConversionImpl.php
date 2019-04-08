<?php

namespace app\modules\v1\service\impl;

use app\modules\v1\common\exception\ValidateException;
use app\modules\v1\domain\StaticConversion;
use app\modules\v1\service\StaticConversionService;

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
        //TODO 接口调用

    }
}
