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
    /** @var int */
    public $design_finish_time;
    /** @var string */
    public $name;
    /** @var string */
    public $stylist;
    /** @var UploadedFile */
    public $imageFile;
    /** @var string */
    public $picture_address;
    /** @var string */
    public $audit_opinion;
    /** @var string */
    public $audit_status;
    /** @var int */
    public $audit_time;
    /** @var string */
    public $size;
    /** @var string */
    public $type;
    /** @var string */
    public $url;
    /** @var  */
    public $category;


    public function rules(): array
    {
        return [
            [['id', 'design_finish_time', 'audit_time', 'audit_status'], 'integer'],
            [['name', 'stylist', 'audit_opinion', 'size', 'type','url','category'], 'string'],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'                => 'ID',
            'design_finish_time'=> '设计完成时间',
            'name'              => '名称',
            'stylist'           => '设计师',
            'imageFile'         => '图片文件',
            'audit_status'      => '审核状态',
            'audit_opinion'     => '审核意见',
            'audit_time'        => '审核时间',
            'size'              => '图片规格',
            'type'              => '类型',
            'url'               => '图片链接',
            'category'          => '属性',
        ];
    }

    /**
     * 上传图片
     * @param string $dirName
     * @return bool|string
     * @throws Exception
     * @author zhuozhen && weifeng
     */
    public function upload(string $dirName)
    {
        $this->imageFile = UploadedFile::getInstanceByName('imageFile');
        if ($this->validate()) {
            $basePath = Yii::$app->basePath . '/web/uploads/designCenter/' . $dirName;
            $ext = $this->imageFile->extension;
            $randName = $this->imageFile->baseName . '_' . time() . random_int(1000, 9999) . '.' . $ext;
            $rootPath = $basePath . '/';
            //判断该目录是否存在
            if (!is_dir($rootPath) && !mkdir($rootPath) && !is_dir($rootPath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $rootPath));
            }
            if ($this->imageFile->saveAs($rootPath . $randName)) {
                return $dirName . '/' . $randName;
            }
            return false;
        }
        return false;
    }
}