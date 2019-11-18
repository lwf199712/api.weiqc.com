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

    /**
     * 审核统计
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return array
     * @author: weifeng
     */
    public function auditStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto): array;

    /**
     * 个人审核统计
     * Date: 2019/11/17
     * Author: ctl
     * @param DesignCenterImageStatisticsDto $designCenterImageStatisticsDto
     * @return array
     */
    public function personalStatistics(DesignCenterImageStatisticsDto $designCenterImageStatisticsDto):array;

}