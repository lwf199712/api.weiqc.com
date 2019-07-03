<?php


namespace app\modules\v1\userAction\enum;


abstract class UrlConvertEnum
{
    /**
     * 链接转换--记录独立IP
     */
    public const REDIS_URL_CONVERT_IP = 'redis_url_convert_ip';

    /**
     * 链接转换--记录独立访客
     */
    public const REDIS_URL_CONVERT_CLIENT = 'redis_url_convert_client';

    /**
     * 链接转换--记录访问数据
     */
    public const REDIS_URL_CONVERT_VISIT = 'redis_url_convert_visit';
}