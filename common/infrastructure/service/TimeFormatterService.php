<?php declare(strict_types=1);


namespace app\common\infrastructure\service;


interface TimeFormatterService
{
    /**
     * 将时间转换为秒数
     * @param string $time 时间，例如：16:00
     * @return float|int
     * @author zhuozhen
     */
    public function toSecond(string $time);
}