<?php
declare(strict_types=1);

namespace app\common\infrastructure\service;


interface ExcelService
{
    public function export() : void ;


    public function import() : void ;
}