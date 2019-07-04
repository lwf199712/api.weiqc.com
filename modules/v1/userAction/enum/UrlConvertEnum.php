<?php


namespace app\modules\v1\userAction\enum;


abstract class UrlConvertEnum
{
    /**
     * 链接转换--记录独立IP
     */
    public const REDIS_URL_CONVERT_IP = 'redis_url_convert_ip';

    /**
     * 链接转换--记录独立IP （备份）
     */
    public const REDIS_URL_CONVERT_IP_BACKUPS = 'redis_url_convert_ip_backups';

    /**
     * 链接转换--记录独立访客
     */
    public const REDIS_URL_CONVERT_CLIENT = 'redis_url_convert_client';

    /**
     * 链接转换--记录独立访客 （备份）
     */
    public const REDIS_URL_CONVERT_CLIENT_BACKUPS = 'redis_url_convert_client_backups';

    /**
     * 链接转换--记录访问数据
     */
    public const REDIS_URL_CONVERT_VISIT = 'redis_url_convert_visit';

    /**
     * 链接转换--记录访问数据 （备份）
     */
    public const REDIS_URL_CONVERT_VISIT_BACKUPS = 'redis_url_convert_visit_backups';


}