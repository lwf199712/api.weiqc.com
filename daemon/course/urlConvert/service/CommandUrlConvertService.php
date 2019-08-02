<?php

/**
 * Interface CommandUrlConvertService
 */
interface CommandUrlConvertService
{
    /**
     * @param array $redisAddUrlConvertDtoList
     * @param string $tableName
     * @return array
     */
    public function batchInsert(array $redisAddUrlConvertDtoList,string $tableName): array;

}