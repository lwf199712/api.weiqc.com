<?php declare(strict_types=1);


namespace app\common\utils;


class IpUtil
{
    public static function ip2int($ip)
    {
        [$ip1, $ip2, $ip3, $ip4] = explode('.', $ip);
        return $ip1 * (256 ** 3) + $ip2 * (256 ** 2) + $ip3 * 256 + $ip4;
    }
}