<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticServiceConversionsPo;
use app\modules\v1\userAction\domain\po\StaticUrlPo;

/**
 * Interface StaticServiceConversionsService
 *
 * @package app\modules\v1\service
 * @author: lirong
 */
interface StaticServiceConversionsService
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
    public function increasedConversions($staticUrl): void;
}
