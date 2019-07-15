<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service;

/**
 * Interface AutoConvertSectionRealtimeMsgService
 * @package app\modules\v1\autoConvert\service
 */
interface AutoConvertSectionRealtimeMsgService
{
    /**
     * @param $condition
     * @return mixed
     * @author zhuozhen
     */
    public function findOne($condition);

    /**
     * @param $condition
     * @return mixed
     * @author zhuozhen
     */
    public function findAll($condition);
}