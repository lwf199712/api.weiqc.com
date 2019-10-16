<?php
declare(strict_types=1);

namespace app\common\infrastructure\service;


interface ExcelService
{
    /**
     * @param array  $data 导出数据
     * @param string $filename
     * @author zhuozhen
     */
    public function export(array $data, string $filename): void;

    /**
     * @param array  $data
     * @param string $filename
     * @return string
     * @author dengkai
     * @date   2019/10/10
     */
    public function exportExcelFile(array $data, string $filename): string;

    /**
     * @param string $filename   文件名
     * @param int    $sheet      视图
     * @param int    $columnCnt  导出列数
     * @param bool   $needHeader 是否需要表头
     * @return array
     * @author zhuozhen
     */
    public function import(string $filename, int $sheet = 0, int $columnCnt = 0, bool $needHeader = false): array;
}