<?php

namespace app\modules\v1\conversion\service;

use app\common\exception\ValidateException;
use app\modules\v1\conversion\domain\po\StaticConversion;

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
