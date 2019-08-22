<?php declare(strict_types=1);

namespace app\common\infrastructure\service\impl;

use app\common\infrastructure\service\DataSetCalculateService;

class DataSetCalculateImpl implements DataSetCalculateService
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
    public function count(array $data, int $startAt, int $endAt, string $interval, int $unitTime, string $field): array
    {
        $result = [];
        if ($startAt > $endAt) {
            return $result;
        }
        array_multisort(array_column($data, $field), SORT_DESC, $data);
        $nextTime = $startAt;
        while (true) {
            $nextTime = strtotime("+{$unitTime} {$interval}", $nextTime);
            foreach ($data as $key => $value) {
                if ($value[$field] < $startAt || $value[$field] >= $nextTime) {
                    continue;
                }
                $result[$startAt]['count']++;
                $result[$startAt]['wxh'][$value['wxh']] = isset($val['wxh']) ? $result[$startAt]['wxh'][$value['wxh']]++ : 0;
            }
            if ($nextTime > $endAt) {
                break;
            }
        }
        return $result;
    }

    public function sum(): array
    {
        // TODO: Implement sum() method.
    }

    public function product(): array
    {
        // TODO: Implement product() method.
    }
}