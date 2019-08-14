<?php
declare(strict_types=1);

namespace app\common\infrastructure\service\impl;


use app\common\infrastructure\service\ExcelService;
use application\modules\common\exception\SpreadSheetException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Component;

/**
 * Class ExcelServiceImpl
 * @property  Spreadsheet $spreadsheet
 * @package app\common\infrastructure\service\impl
 */
class ExcelServiceImpl extends Component implements ExcelService
{
    /* @var int 异常code */
    private const EXCEPTION_CODE = 500;

    /** @var Spreadsheet */
    public $spreadsheet;


    /**
     * @param array  $data 导出数据
     * @param string $filename
     * @throws SpreadSheetException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @author zhuozhen
     */
    public function export(array $data,string $filename): void
    {
        $this->spreadsheet = $this->getXlsxTemplate($data);
        $writer            = new Xlsx($this->spreadsheet);
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
        header('Content-Type:application/force-download');
        header('Content-Type:application/vnd.ms-execl');
        header('Content-Type:application/octet-stream');
        header('Content-Type:application/download');
        header('Content-Type: application/vnd.ms-excel;charset=utf-8');
        header('Content-Disposition:attachment;filename="$filename.xlsx"');
        header('Content-Transfer-Encoding:binary');
        $writer->save('php://output');
    }

    /**
     * @param string $filename
     * @param int    $sheet
     * @param int    $columnCnt
     * @param bool   $needHeader
     * @return array
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws SpreadSheetException
     * @author zhuozhen
     */
    public function import(string $filename, int $sheet = 0, int $columnCnt = 0, bool $needHeader = false): array
    {
        $this->spreadsheet = IOFactory::load($filename);
        $currSheet         = $this->spreadsheet->getSheet($sheet);

        if (0 === $columnCnt) {
            /* 取得最大的列号 */
            $columnH = $currSheet->getHighestColumn();
            /* 兼容原逻辑，循环时使用的是小于等于 */
            $columnCnt = Coordinate::columnIndexFromString($columnH);
        }

        /* 获取总行数 */
        $rowCnt = $currSheet->getHighestRow();
        $data   = [];

        /* 读取内容 */
        for ($_row = 1; $_row <= $rowCnt; $_row++) {
            $isNull = true;

            for ($_column = 1; $_column <= $columnCnt; $_column++) {
                $cellName = Coordinate::stringFromColumnIndex($_column);
                $cellId   = $cellName . $_row;
                $cell     = $currSheet->getCell($cellId);
                if ($cell === null) {
                    throw new SpreadSheetException("单元格$cellId 异常");
                }
                $data[$_row][$cellName] = trim($cell->getFormattedValue());

                if (!empty($data[$_row][$cellName])) {
                    $isNull = false;
                }
            }
            if ($isNull) {
                unset($data[$_row]);
            }
        }
        if ($needHeader === false) {
            unset($data[1]);  //去除表头
        }
        return $data;
    }


    /**
     * @param $source
     * @return Spreadsheet
     * @throws SpreadSheetException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author zhuozhen
     */
    private function getXlsxTemplate($source): Spreadsheet
    {
        if (!is_array($source) || !is_array(current($source))) {
            throw new SpreadSheetException('导出模板失败!!模板数据类型错误!', self::EXCEPTION_CODE);
        }
        //生成文档
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        //置字体大小为10
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $sheet->fromArray($source, NULL);

        //样式=边框线+对准
        $sheet->getStyle('A1:' . $this->intToChr(count(current($source)) - 1) . count($source))->applyFromArray(
            [
                'borders'   => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => '00000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ]
        );
        //设置宽度
        for ($column = 1; $column <= (count(current($source)) - 1); $column++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($this->intToChr($column))->setAutoSize(true);
        }
        return $spreadsheet;
    }

    /**
     * v1:数字转字母 （Excel列标）
     *
     * @param int $index 索引值
     * @param int $start 字母起始值
     * @return string 返回字母
     * @author lirong
     */
    private function intToChr($index, $start = 65): string
    {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= $this->intToChr(floor($index / 26) - 1);
        }
        return $str . chr($index % 26 + $start);
    }
}