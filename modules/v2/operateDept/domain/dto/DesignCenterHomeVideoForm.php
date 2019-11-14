<?php declare(strict_types=1);


namespace app\modules\v2\operateDept\domain\dto;

use app\components\UploadFile;
use phpDocumentor\Reflection\Types\Object_;
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
    /** @var string */
    public $video;
    /** @var string */
    public $audit_opinion;
    /** @var string */
    public $audit_status;
    /** @var int */
    public $audit_time;
    /** @var string */
    public $url;
    /** @var string*/
    public $category;

    public function rules() :array
    {
        return [
            [['id', 'design_finish_time', 'audit_time', 'audit_status'], 'integer'],
            [['name', 'audit_opinion','url','category'], 'string'],
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
        ];
    }

    /**
     * 你有钱就用oss
     * Date: 2019/10/30
     * Author: ctl
     * @return string
     */
//    public function upload()
//    {
//        // 判断有没有上传视频
//        if (!UploadedFile::getInstanceByName('videoFile')){
//            return false;
//        }
//        $this->videoFile = UploadedFile::getInstanceByName('videoFile');
//        $file['video'] = json_decode(json_encode($this->videoFile),true);
//        $file['video']['tmp_name'] = $file['video']['tempName'];
//        unset($file['video']['tempName']);
////        $upload = new UploadFile($file);
//        try {
//            $url = $this->uploadVideo('',$file);
////            $url = $upload->upload('oss', 'gdt/operateDept/video/');
//            return $url;
//        } catch (\OSS\Core\OssException $ossException) {
//            throw new $ossException->getMessage();
//        } catch (\Exception $e) {
//            throw new $e->getMessage();
//        }
//    }

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
        if (!$this->videoFile){
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
}