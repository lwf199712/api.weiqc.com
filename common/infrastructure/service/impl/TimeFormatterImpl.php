<?php declare(strict_types=1);


namespace app\common\infrastructure\service\impl;


use app\common\infrastructure\service\TimeFormatterService;

class TimeFormatterImpl implements TimeFormatterService
{
    /**
     * 将时间转换为秒数
     * @param string $time 时间，例如：16:00
     * @return float|int
     * @author zhuozhen
     */
    public function toSecond(string $time)
    {
        $day   = strtotime(date('Y-m-d'));
        $shift = strtotime($time);
        return abs($shift - $day);
    }
}