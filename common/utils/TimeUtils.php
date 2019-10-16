<?php
declare(strict_types=1);

namespace app\common\utils;


use RuntimeException;

class TimeUtils
{
    /**
     * 获取日期当天开始时间戳
     * @param string $time
     * @return int
     * @author dengkai
     * @date   2019/9/27
     */
    public static function getBeginTimeStamp(string $time = null): int
    {
        return empty($time) ? strtotime(date('Y-m-d')) : strtotime($time);
    }

    /**
     * 获取日期当天结束时间戳
     * @param string $time
     * @return int
     * @author dengkai
     * @date   2019/9/27
     */
    public static function getEndTimeStamp(string $time = null): int
    {
        if (empty($time)) {
            $timeStamp = strtotime('+1 day', strtotime(date('Y-m-d'))) - 1;
        } else {
            $timeStamp = strtotime('+1 day', strtotime($time)) - 1;
        }

        return $timeStamp;
    }

    /**
     * 获取指定日期的开始和结束时间戳
     * @param string $str today-今天/yesterday-昨天/beforeYesterday-前天/thisMonth-这个月/lastMonth-上个月
     * @return array
     * @author dengkai
     * @date   2019/9/27
     */
    public static function getSpecifiedTimeStamp(string $str): array
    {
        switch ($str) {
            case 'today':
                $begin = strtotime(date('Y-m-d'));
                $end = strtotime('+1 day', $begin) - 1;
                break;
            case 'yesterday':
                $begin = strtotime('-1 day', strtotime(date('Y-m-d')));
                $end = strtotime('+1 day', $begin) - 1;
                break;
            case 'beforeYesterday':
                $begin = strtotime('-2 day', strtotime(date('Y-m-d')));
                $end = strtotime('+1 day', $begin) - 1;
                break;
            case 'thisMonth':
                $begin = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
                $end = mktime(23, 59, 59, (int)date('m'), (int)date('t'), (int)date('Y'));
                break;
            case 'lastMonth':
                $thisMonth = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
                $begin = strtotime('-1 month', $thisMonth);
                $end = $thisMonth - 1;
                break;
            default:
                throw new RuntimeException(static::class . '/' . __FUNCTION__ . ',the parameter can not match option.', 500);
                break;
        }

        return ['beginTime' => $begin, 'endTime' => $end];
    }
}