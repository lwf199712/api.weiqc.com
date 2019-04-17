<?php

namespace app\daemon\sourse\conversion\service;

/**
 * Interface CommandsStaticConversionService
 *
 * @package app\commands\conversion\service
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
