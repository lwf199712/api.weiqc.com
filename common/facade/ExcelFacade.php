<?php
declare(strict_types=1);

namespace app\common\facade;


use app\common\core\BaseFacade;
use app\common\infrastructure\service\ExcelService;

/**
 * @method static export(array $data)
 */
class ExcelFacade extends BaseFacade
{
    /**
     *  @return object|string|array|null
     */
    protected static function getFacadeAccessor()
    {
        return ExcelService::class;
    }


}