<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\dto;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class IndexImgForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $version;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;
    /** @var string */
    public $size;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['version', 'name', 'stylist'], 'required'],
            [['id','version', 'name', 'stylist','size'], 'string'],
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
            'size' => '规格大小',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $basePath = Yii::$app->basePath . '/web/uploads/designCenter/index-img/';
            $ext = $this->imageFile->extension;
            $randName = $this->imageFile->baseName.'_'.time() . mt_rand(1000, 9999) . '.' . $ext;
            $rootPath = $basePath . '/';
            //判断该目录是否存在
            if (!is_dir($rootPath)) {
                mkdir($rootPath);
//                throw new \RuntimeException(sprintf('Directory "%s" was not created', $rootPath));
            }
            $re = $this->imageFile->saveAs($rootPath . $randName);
            if ($re) {
                return $randName;
            }
            return false;
        }
        return false;
    }

}