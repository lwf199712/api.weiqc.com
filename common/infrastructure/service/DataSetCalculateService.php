<?php declare(strict_types=1);


namespace app\common\infrastructure\service;

/**
 * 统计数据集服务
 * Interface DataSetCalculateService
 * @package app\common\infrastructure\service
 */
interface DataSetCalculateService
{
    /**
     * 统计数据集条数
     * @param array  $data     输入数据
     * @param int    $startAt  开始时间
     * @param int    $endAt    结束时间
     * @param string $interval 统计时间差单位(IntervalEnum)
     * @param int    $unitTime 单位时间差(默认1)
     * @param string $field
     * @return array
     * @author zhuozhen
     */
    public function count(array $data, int $startAt, int $endAt, string $interval, int $unitTime, string $field): array;

    public function sum(): array;

    public function product(): array;
}