<?php

namespace app\modules\conversion\service;

use app\exception\ValidateException;
use app\modules\conversion\domain\StaticServiceConversions;
use app\modules\conversion\domain\StaticUrl;

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
