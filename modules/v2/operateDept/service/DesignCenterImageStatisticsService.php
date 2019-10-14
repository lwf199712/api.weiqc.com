<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\service;

use app\modules\v2\operateDept\domain\dto\DesignCenterImageStatisticsDto;

interface DesignCenterImageStatisticsService
{
    /**
     * 图片统计列表
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return mixed
     * @author: weifeng
     */
    public function listImage(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): array;

}