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
    public function findOne($condition);

    /**
     * @param mixed $condition
     * @return boolean|null|mixed
     * @author: lirong
     */
    public function exists($condition);

    /**
     * @param StaticConversionPo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public function insert($staticConversionPo):int;
}
