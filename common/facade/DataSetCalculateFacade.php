<?php declare(strict_types=1);


namespace app\common\facade;


use app\common\core\BaseFacade;
use app\common\infrastructure\service\DataSetCalculateService;

/**
 * 统计数据集门面
 * Class DataSetCalculateFacade
 * @method static count(array $data, int $startAt, int $endAt, string $interval, int $unitTime, string $field) : array
 * @package app\common\facade
 */
class DataSetCalculateFacade extends BaseFacade
{
    /**
     *  @return object|string|array|null
     */
    protected static function getFacadeAccessor()
    {
        return DataSetCalculateService::class;
    }
}