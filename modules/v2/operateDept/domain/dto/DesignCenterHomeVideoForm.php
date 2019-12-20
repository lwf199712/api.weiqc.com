<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;

use app\components\UploadFile;
use yii\base\Model;
use Exception;
use RuntimeException;
use Yii;
use yii\web\UploadedFile;

class DesignCenterHomeVideoForm extends Model
{
    /** @var int */
    public $id;
    /** @var int */
    public $design_finish_time;
    /** @var string */
    public $name;
    /** @var UploadedFile */
    public $videoFile;
    /** @var UploadedFile */
    public $imageFile;
    /** @var string */
    public $video;
    /** @var string */
    public $audit_opinion = '';
    /** @var string */
    public $audit_status;
    /** @var string */
    public $url;
    /** @var string*/
    public $category;
    /** @var string*/
    public $thumbnail;

    public function rules() :array
    {
        return [
            [['id', 'design_finish_time','audit_status'], 'integer'],
            [['name', 'audit_opinion','url','category', 'thumbnail'], 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'                => 'ID',
            'design_finish_time'=> '设计完成时间',
            'name'              => '名称',
            'videoFile'         => '视频文件',
            'audit_status'      => '审核状态',
            'audit_opinion'     => '审核意见',
            'audit_time'        => '审核时间',
            'url'               => '视频链接',
            'category'          => '属性',
            'thumbnail'         => '缩略图',
        ];
    }

    /**
     * delete oss video
     * Date: 2019/11/1
     * Author: ctl
     * @param $address
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function delete($address): bool
    {
        $res = UploadFile::deleteOssFile($address);
        if ($res){
            return true;
        }
        return false;
    }

    /**
     * 删除本地视频
     * Date: 2019/11/14
     * Author: ctl
     * @param $address
     * @return bool
     */
    public function deletelocal($address):bool
    {
        $filePath = explode("/uploads/",$address)[1];
        $address = Yii::$app->basePath.'/web/uploads/'.$filePath;
        return unlink($address);
    }

    /**
     * 删除本地图片
     * Date: 2019/11/14
     * Author: ctl
     * @param $address
     * @return bool
     */
    public function deleteImage($address):bool
    {
        $filePath = explode('/uploads/designCenter/thumbnail/',$address)[1];
        $address = Yii::$app->basePath.'/web/uploads/designCenter/thumbnail/'.$filePath;
        return unlink($address);
    }

    /**
     * 视频上传到本地
     * Date: 2019/11/13
     * Author: ctl
     * @param string $dirName
     * @return string
     * @throws Exception
     */
    public function uploadVideo(string $dirName = 'video')
    {
        $this->videoFile = UploadedFile::getInstanceByName('videoFile');
        if ($this->videoFile === null){
            return false;
        }
        if ($this->validate()) {
            $basePath = Yii::$app->basePath . '/web/uploads/' . $dirName;
            $ext = $this->videoFile->extension;
            $randName = $this->videoFile->baseName . '_' . time() . random_int(1000, 9999) . '.' . $ext;
            $rootPath = $basePath . '/';
            //判断该目录是否存在
            if (!is_dir($rootPath) && !mkdir($rootPath) && !is_dir($rootPath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $rootPath));
            }

            $res = $this->videoFile->saveAs($rootPath . $randName);
            if ($res) {
                return '/uploads/' . $dirName . '/' . $randName;
            }
        }
        return false;
    }

    /**
     * 上传图片
     * @return bool|string
     * @throws Exception
     * @author weifeng
     */
    public function uploadImage()
    {
        $this->imageFile = UploadedFile::getInstanceByName('imageFile');
        if ($this->validate()) {
            $basePath = Yii::$app->basePath . '/web/uploads/designCenter/thumbnail';
            $ext = $this->imageFile->extension;
            $randName = $this->imageFile->baseName . '_' . time() . random_int(1000, 9999) . '.' . $ext;
            $rootPath = $basePath . '/';
            //判断该目录是否存在
            if (!is_dir($rootPath) && !mkdir($rootPath) && !is_dir($rootPath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $rootPath));
            }
            if ($this->imageFile->saveAs($rootPath . $randName)) {
                return '/' . $randName;
            }
            return false;
        }
        return false;
    }
}