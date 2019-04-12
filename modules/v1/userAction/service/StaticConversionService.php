<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticConversionPo;

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
     * @return StaticConversionPo|null|mixed
     * @author: lirong
     */
    public static function findOne($condition);

    /**
     * @param StaticConversionPo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public static function insert($staticConversionPo):int;
}
