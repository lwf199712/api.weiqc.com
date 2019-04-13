<?php

namespace app\utils;

/**
 * Class WindowsUtils
 *
 * @package app\modules\v1\utils
 * @author: lirong
 */
class RequestUtils
{
    public const MOBILE_LIST = [
        'iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
        'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
        'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
        'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
        'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
        'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
        'benq', 'haier', '^lct', '320x320', '240x320', '176x220'
    ];

    public const PC_LIST = ['mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop'];

    /**
     * Check if the request comes from the phone
     *
     * @return bool
     * @author: lirong
     */
    public function requestFromMobile(): bool
    {
        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (in_array($useragent, self::MOBILE_LIST, false)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the request comes from pc
     *
     * @return bool
     * @author: lirong
     */
    public function requestFromPc(): bool
    {
        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (in_array($useragent, self::PC_LIST, false)) {
            return true;
        }
        return false;
    }
}