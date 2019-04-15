<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\models\po\StaticServiceConversionsPo;
use app\models\po\StaticUrlPo;

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
     * @return StaticServiceConversionsPo|null|mixed
     * @author: lirong
     */
    public function findOne($condition);

    /**
     * @param StaticUrlPo $staticUrl
     * @return void
     * @throws ValidateException
     * @author: lirong
     */
    public function increasedConversions(StaticUrlPo  $staticUrl): void;
}
