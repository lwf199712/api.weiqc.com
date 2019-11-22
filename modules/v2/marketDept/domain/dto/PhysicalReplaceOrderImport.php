<?php declare(strict_types=1);


namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;
use yii\web\UploadedFile;

class PhysicalReplaceOrderImport extends Model
{
    /**
     * @var UploadedFile
     */
    public $excelFile;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'csv,xlsx,xls'],
        ];
    }

    /**
     * 注册字段
     * @return array
     */

    public function attributeLabels()
    {
        return [
            'excelFile' => 'excel文件'
        ];
    }
}