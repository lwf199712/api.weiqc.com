<?php

namespace app\daemon\conversionCommands\service;

/**
 * Interface CommandsStaticConversionService
 *
 * @package app\commands\conversionCommands\service
 * @author: lirong
 */
interface CommandsStaticUrlService
{
    /**
     * @param $condition
     * @return array
     * @author: lirong
     */
    public function findAll($condition): array;
}
