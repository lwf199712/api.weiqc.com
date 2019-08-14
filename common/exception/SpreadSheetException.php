<?php
declare(strict_types=1);

namespace application\modules\common\exception;

use yii\base\Exception;

class SpreadSheetException extends Exception
{
    /**
     * v1:获取异常名称
     *
     * @return string
     * @author lirong
     */
    public function getName(): string
    {
        return 'SpreadSheet Exception';
    }
}