<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class DesignCenterForm extends Model
{
    public $id;

    public $version;

    public $name;

    public $stylist;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['version', 'name', 'stylist'], 'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'version' => '版本',
            'name' => '名称',
            'stylist' => '设计师',
            'imageFile' => '图片文件',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::$app->basePath.'/uploads/designCenter/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        }
        return false;
    }
}