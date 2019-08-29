<?php declare(strict_types=1);


namespace app\common\facade;


use app\common\core\BaseFacade;
use app\common\infrastructure\service\TimeFormatterService;

/**
 * 时间格式化门面
 * Class TimeFormatterFacade
 * @method static toSecond(string $time)
 * @package app\common\facade
 */
class TimeFormatterFacade extends BaseFacade
{
    /**
     *  @return object|string|array|null
     */
    protected static function getFacadeAccessor()
    {
        return TimeFormatterService::class;
    }
}