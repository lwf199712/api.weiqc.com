<?php

namespace app\commands\conversionCommands\service;


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
