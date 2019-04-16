<?php

namespace app\commands\conversionCommands\service;

/**
 * Interface CommandsStaticConversionService
 *
 * @package app\commands\conversionCommands\service
 * @author: lirong
 */
interface CommandsStaticConversionService
{
    /**
     * @param $condition
     * @return array
     * @author: lirong
     */
    public function findAll($condition): array;
}
