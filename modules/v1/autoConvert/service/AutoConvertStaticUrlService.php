<?php
declare(strict_types=1);

namespace app\modules\v1\autoConvert\service;

use app\models\dataObject\StaticUrlDo;

/**
 * Interface AutoConvertStaticUrlService
 * @property StaticUrlDo $staticUrlDo
 * @package app\modules\v1\autoConvert\service
 */
interface AutoConvertStaticUrlService
{

    /**
     * 获取特定模式下公众号对应的url
     * @param string $currentDept
     * @return array
     * @author zhuozhen
     */
    public function getServiceUrl(string $currentDept): array;

    /**
     * @param int    $id
     * @param string $url
     * @param string $pcUrl
     * @param string $oldDept
     * @param string $newDept
     * @return int
     * @author zhuozhen
     */
    public function updateUrl(int $id, string $url, string $pcUrl, string $oldDept, string $newDept): int ;

}