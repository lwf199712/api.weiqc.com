<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class DesignCenterForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $version;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['version', 'name', 'stylist'], 'required'],
            [['id','version', 'name', 'stylist'], 'string'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'version' => '版本',
            'name' => '名称',
            'stylist' => '设计师',
            'imageFile' => '图片文件',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::$app->basePath . '/web/uploads/designCenter/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        }
        return false;
    }
}