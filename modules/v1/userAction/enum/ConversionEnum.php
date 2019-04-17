<?php

namespace app\modules\v1\userAction\enum;

abstract class ConversionEnum
{
    /**
     * 点击数增加队列
     * @var string
     * @author lirong
     */
    public const REDIS_ADD_VIEW = 'redis_add_view';
    /**
     * 点击数增加暂存队列
     * @var string
     * @author lirong
     */
    public const REDIS_ADD_VIEW_BACKUPS = 'redis_add_view_backups';
}