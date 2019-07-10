<?php
declare(strict_types=1);
namespace app\modules\v1\autoConvert\enum;


use app\components\core\BaseEnum;

/**
 * @time     粉丝进入的当前时间戳
 * @current  粉丝进入的序号
 * @halfhour 半小时前的粉丝数
 * Class Message
 * @method static getTime(string $DC_REAL_TIME_MESSAGE)
 * @method static getCurrent(string $DC_REAL_TIME_MESSAGE)
 * @method static getHalfHour(string $DC_REAL_TIME_MESSAGE)
 */
class MessageEnum extends BaseEnum
{
    /**
     * @time('_timeStamp')
     * @current('_currentFansCount')
     * @halfHour('_currentThirtyMinInitVal')
     */
    public const DC_REAL_TIME_MESSAGE = 'dc_real_time_message';


}