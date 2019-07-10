<?php


namespace app\modules\v1\autoConvert\service;

use yii\db\Exception;

interface AutoConvertStaticConversionService
{
    /**
     * 切换公众号时更新service字段
     * @param int    $id
     * @param string $service
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateService(int $id,string $service) : bool ;
}