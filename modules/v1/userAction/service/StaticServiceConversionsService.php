<?php

namespace app\modules\v1\userAction\service;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticServiceConversions;
use app\modules\v1\userAction\domain\po\StaticUrl;

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
     * @return StaticServiceConversions|null|mixed
     * @author: lirong
     */
    public static function findOne($condition);

    /**
     * @param StaticUrl $staticUrl
     * @return void
     * @throws ValidateException
     * @author: lirong
     */
    public static function increasedConversions($staticUrl): void;
}
