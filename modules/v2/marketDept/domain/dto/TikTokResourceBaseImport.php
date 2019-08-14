<?php
declare(strict_types=1);

namespace app\modules\v2\marketDept\domain\dto;


use yii\base\Model;
use yii\web\UploadedFile;

class TikTokResourceBaseImport extends Model
{
    /**
     * @var UploadedFile
     */
    public $excelFile;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'csv,xlsx'],
        ];
    }

    public function attributeLabels()
    {
        return [
           'excelFile' => 'excel文件'
        ];
    }
}