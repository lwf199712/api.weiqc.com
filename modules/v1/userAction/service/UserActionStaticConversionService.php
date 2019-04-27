<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\models\dataObject\StaticConversionDo;

/**
 * Interface UserActionStaticConversionService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface UserActionStaticConversionService
{
    /**
     * @param mixed $condition
     * @return StaticConversionDo|null|mixed
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
     * @param StaticConversionDo $staticConversionPo
     * @return int
     * @throws ValidateException
     * @author: lirong
     */
    public function insert($staticConversionPo):int;
}
