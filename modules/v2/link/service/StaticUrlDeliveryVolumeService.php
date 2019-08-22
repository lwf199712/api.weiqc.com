<?php declare(strict_types=1);


namespace app\modules\v2\link\service;

use app\modules\v2\link\domain\aggregate\DeliveryVolumeAggregate;
use app\modules\v2\link\domain\aggregate\StaticListAggregate;
use app\modules\v2\link\domain\dto\DeliveryVolumeDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeForm;
use app\modules\v2\link\domain\dto\DeliveryVolumeGeneratedDto;
use app\modules\v2\link\domain\dto\DeliveryVolumeHandledDto;

/**
 * Class StaticUrlDeliveryVolumeService
 * @property-read StaticListAggregate     $staticListAggregate
 * @property-read DeliveryVolumeAggregate $deliveryVolumeAggregate
 * @package app\modules\v2\link\service
 */
interface StaticUrlDeliveryVolumeService
{
    /**
     * 处理提交的投放量数据
     * @param DeliveryVolumeForm $deliveryVolumeForm
     * @return DeliveryVolumeHandledDto
     * @author zhuozhen
     */
    public function processDeliveryVolumeData(DeliveryVolumeForm $deliveryVolumeForm) :  DeliveryVolumeHandledDto ;


    /**
     * 生成投放数据
     * @param DeliveryVolumeDto $deliveryVolumeDto
     * @return DeliveryVolumeGeneratedDto
     * @author zhuozhen
     */
    public function generateDeliveryData(DeliveryVolumeDto $deliveryVolumeDto) : DeliveryVolumeGeneratedDto;


    /**
     * 统计受访页面URL
     * @param array  $hits
     * @param string $groupBy
     * @return array
     * @author zhuozhen
     */
    public function countPage(array $hits,string $groupBy): array;
}