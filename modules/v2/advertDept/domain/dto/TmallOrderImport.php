<?php declare(strict_types=1);

namespace app\modules\v2\advertDept\domain\dto;


use yii\base\Model;
use yii\web\UploadedFile;

class TmallOrderImport extends Model
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