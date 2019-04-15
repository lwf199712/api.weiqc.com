<?php

namespace app\commands\conversionCommands\service;

use app\common\exception\ValidateException;
use app\modules\v1\userAction\domain\po\StaticConversionPo;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Interface CommandsStaticHitsService
 *
 * @package app\modules\v1\userAction\service
 * @author: lirong
 */
interface CommandsStaticHitsService
{
    /**
     * batch insert
     *
     * @param array $staticHitsList
     * @return void
     * @author: lirong
     */
    public function batchInsert(array $staticHitsList): void;
}
