<?php
declare(strict_types = 1);

namespace app\modules\v1\autoConvert\service;

interface ChangeService
{
    /**
     * 转粉程序
     * @param string $currentDept
     * @param string $lackFansDept
     * @param AutoConvertStaticUrlService $autoConvertStaticUrlService
     * @param AutoConvertStaticConversionService $autoConvertStaticConversionService
     * @return bool
     * @author zhuozhen
     */
    public function __invoke(string $currentDept,
                             string $lackFansDept,
                             AutoConvertStaticUrlService $autoConvertStaticUrlService,
                             AutoConvertStaticConversionService $autoConvertStaticConversionService) : bool ;
}