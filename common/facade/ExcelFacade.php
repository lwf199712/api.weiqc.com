<?php
declare(strict_types=1);

namespace app\common\facade;


use app\common\core\BaseFacade;
use app\common\infrastructure\service\ExcelService;

/**
 * @method static export(array $data, string $filename, array $lineFeed = [], int $mergeNum = 0)
 * @method static exportExcelFile(array $data, string $filename, int $mergeNum = 0)
 * @method static import(string $name, int $sheet = 0, int $columnCnt = 0, $needHeader = false)
 */
class ExcelFacade extends BaseFacade
{
    /**
     * @return object|string|array|null
     */
    protected static function getFacadeAccessor()
    {
        return ExcelService::class;
    }


}