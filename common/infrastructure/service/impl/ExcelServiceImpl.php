<?php
declare(strict_types=1);

namespace app\common\infrastructure\service\impl;


use app\common\infrastructure\service\ExcelService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\base\Component;

/**
 * Class ExcelServiceImpl
 * @property  Spreadsheet $spreadsheet
 * @package app\common\infrastructure\service\impl
 */
class ExcelServiceImpl extends Component implements ExcelService
{
    /** @var Spreadsheet */
    public $spreadsheet;

    public function __construct($config = [])
    {
        $this->spreadsheet = new Spreadsheet();
        parent::__construct($config);
    }

    public function export(): void
    {
        // TODO: Implement export() method.
    }

    public function import(): void
    {
        // TODO: Implement import() method.
    }
}