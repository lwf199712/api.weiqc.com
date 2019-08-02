<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\models\dataObject\StaticServiceConversionsDo;
use app\models\dataObject\StaticUrlDo;

/**
 * Interface UserActionStaticServiceConversionsService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface UserActionStaticServiceConversionsService
{
    /**
     * @param mixed $condition
     * @return StaticServiceConversionsDo|null|mixed
     * @author: lirong
     */
    public function findOne($condition);

    /**
     * @param StaticUrlDo $staticUrl
     * @return void
     * @throws ValidateException
     * @author: lirong
     */
    public function increasedConversions(StaticUrlDo  $staticUrl): void;
}
