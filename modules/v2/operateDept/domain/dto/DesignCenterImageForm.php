<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;


use Exception;
use RuntimeException;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class DesignCenterImageForm
 * @package app\modules\v2\operateDept\domain\dto
 */
class DesignCenterImageForm extends Model
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;
    /** @var UploadedFile */
    public $imageFile;
    /** @var string */
    public $size;
    /** @var string */
    public $audit;
    /** @var string */
    public $auditSuggess;


    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'stylist', 'size'], 'string'],
            ['audit', 'in', 'range' => ['0', '1']],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'           => 'ID',
            'name'         => '名称',
            'stylist'      => '设计师',
            'imageFile'    => '图片文件',
            'size'         => '规格',
            'audit'        => '审核结果',
            'auditSuggess' => '审核意见',
        ];
    }

    /**
     * 上传图片
     * @param string $dirName
     * @return bool|string
     * @throws Exception
     * @author zhuozhen
     */
    public function upload(string $dirName)
    {
        $this->imageFile = UploadedFile::getInstanceByName('imageFile');
        if ($this->validate()) {
            $basePath = Yii::$app->basePath . '/web/uploads/designCenter/' . $dirName;
            $ext      = $this->imageFile->extension;
            $randName = $this->imageFile->baseName . '_' . time() . random_int(1000, 9999) . '.' . $ext;
            $rootPath = $basePath . '/';
            //判断该目录是否存在
            if (!is_dir($rootPath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $rootPath));
            }
            if ($this->imageFile->saveAs($rootPath . $randName)) {
                return $randName;
            }
            return false;
        }
        return false;
    }
}