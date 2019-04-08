<?php

namespace app\modules\v1\service;

use app\modules\v1\common\exception\ValidateException;
use app\modules\v1\domain\StaticConversion;

/**
 * Interface StaticConversionService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface StaticConversionService
{
    /**
     * @param mixed $condition
     * @return StaticConversion|null|mixed
     * @author: lirong
     */
    public static function findOne($condition);

    /**
     * @param StaticConversion $staticConversion
     * @return void
     * @throws ValidateException
     * @author: lirong
     */
    public static function insert($staticConversion):void;
}
