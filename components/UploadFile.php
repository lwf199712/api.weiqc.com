<?php
/**
 * Created by: FengDaJin
 * Date: 2018/9/25
 */
namespace app\components;

use Yii;

/**
 * Class UploadImage
 *
 * @package components
 */
class UploadFile
{
    const MAXSIZE = 52428800;

    private $file;

    protected $fileType = array('image/jpg', 'image/gif', 'image/png', 'imgage/bmp', 'image/jpeg',
        'video/mp4', 'video/webm', 'video/ogg');

    public function __construct(array $file)
    {
        $this->file = current($file);
    }

    /**
     * 检查是否有文件上传
     *
     * @throws \Exception
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function checkHasFile()
    {
        if (empty(current($this->file['name']))) {
            throw new \Exception('请选择上传文件');
        }
    }

    /**
     * 检查上传的文件是否有错误
     *
     * @throws \Exception
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function checkFileIsError()
    {
        foreach ($this->file['error'] as $key => $item) {
            if ($item) {
                throw new \Exception('文件：'. $this->file['name'][$key] . '上传错误');
            }
        }
    }

    /**
     * 检查文件的类型
     *
     * @throws \Exception
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function checkFileType()
    {
        foreach ($this->file['type'] as $key => $item) {
            if (!in_array($item, $this->fileType)) {
                throw new \Exception('文件格式：' . $item . '上传错误');
            }
        }
    }

    /**
     * 检查文件的类型
     *
     * @throws \Exception
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function checkFileSize()
    {
        foreach ($this->file['size'] as $key => $item) {
            if ($item > self::MAXSIZE) {
                throw new \Exception('文件：' . $this->file['name'][$key] . '不能超过' . MAXSIZE/1024/1024 . 'M');
            }
        }
    }

    /**
     * 获取文件的类型
     *
     * @param $file
     * @return mixed
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function getFileExtension($file)
    {
        return pathinfo($file)['extension'];
    }

    /**
     * 将单文件上传的转为多文件的形式，为了适配多文件
     *
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function getFile()
    {
        if (!is_array($this->file['name'])) {
            foreach ($this->file as $key => $item) {
                $this->file[$key] = array($item);
            }
        }
    }

    /**
     * 获取保存的路径
     *
     * @param string $basePath
     * @return string
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function getSavePath($basePath = '')
    {
        $path = @date('Ymd') . '/';
        $basePath = rtrim($basePath, '/') . '/';
        return empty($basePath) ? 'default/' . $path : $basePath . $path;
    }

    /**
     * 保存文件的文件名
     *
     * @param string $name
     * @return string
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function getSaveFileName($name = '')
    {
        return empty($name) ? date('YmdHis') . '_' . rand(10000, 99999) : $name;
    }

    /**
     * 上传到阿里云的oss
     *
     * @param $savePath
     * @param $saveName
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $endpoint
     * @param string $bucket
     * @return array
     * @throws \OSS\Core\OssException
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function uploadToOss($savePath, $saveName, $accessKeyId = '', $accessKeySecret = '', $endpoint = '', $bucket = '')
    {
        $oss = new Oss($accessKeyId, $accessKeySecret, $endpoint, $bucket);
        $savePath = $this->getSavePath($savePath);
        $url = [];
        foreach ($this->file['tmp_name'] as $key => $item) {
            $saveName = $this->getSaveFileName($saveName) . '.' . $this->getFileExtension($this->file['name'][$key]);
            $url[] = $oss->saveToOss($savePath . $saveName, $item);
        }
        return $url;
    }

    /**
     * 上传到本地
     *
     * @author FengDaJin
     * @date 2018/9/25
     * @param $savePath
     * @param string $saveName
     * @return array
     * @throws \Exception
     */
    public function uploadToLocal($savePath, $saveName = '')
    {
        $url = [];
        foreach ($this->file['tmp_name'] as $key => $item) {
            $saveName = $this->getSaveFileName($saveName) . '.' . $this->getFileExtension($this->file['name'][$key]);
            $url[] = $this->saveToLocal($savePath . $saveName, $item);
        }
        return $url;
    }

    /**
     * 保存到本地
     * @param $savePath
     * @param $item
     * @param bool $isCustom 是否为自定义绝对文件路径
     * @return string
     * @throws \Exception
     */
    public function saveToLocal($savePath, $item, $isCustom = false)
    {
        if (!$savePath || !$item){throw new \Exception('检查是否有选择文件!');}
        if ($isCustom){
            //TODO

        }else {
            //TODO
        }
    }

    /**
     * 上传的方法
     *
     * @param $method
     * @param $savePath
     * @param string $saveName
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $endpoint
     * @param string $bucket
     * @return array|void
     * @throws \OSS\Core\OssException
     * @throws \Exception
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function upload($method, $savePath, $saveName = '', $accessKeyId = '', $accessKeySecret = '', $endpoint = '', $bucket = '')
    {
        $this->getFile();
        $this->checkHasFile();
        $this->checkFileIsError();
        $this->checkFileType();
        $this->checkFileSize();
        if ($method == 'oss') {
            return $this->uploadToOss($savePath, $saveName, $accessKeyId, $accessKeySecret, $endpoint, $bucket);
        } elseif ($method == 'local') {
            return $this-> uploadToLocal($savePath, $saveName);
        }
    }


    /**
     * 解析图片路径
     * @param string $url
     * @param string $type
     * @return string
     */
    public static function parseUrl(string $url, string $type) : string
    {
        switch ($type) {
            case 'good' :
                $dirName = '/assets/goods/';
                break;
            case 'none' :
                $dirName = '';
                break;
            case 'bloggerArticle':
                $dirName = '/assets/bloggerArticle/';
                break;
            default:
                $dirName = '/assets/';
        }
        return strpos($url, 'oss') ? $url : Yii::$app->request->hostInfo . $dirName . $url;
    }

    /**
     * delete ossFile
     * Date: 2019/11/1
     * Author: ctl
     * @param $video
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $endpoint
     * @param string $bucket
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public static function deleteOssFile($video,$accessKeyId = '', $accessKeySecret = '', $endpoint = '', $bucket = ''): bool
    {
        $oss = new Oss($accessKeyId, $accessKeySecret, $endpoint, $bucket);
        $ossObject = substr(parse_url($video)['path'],1);
        $res = $oss->deleteToOss($ossObject);
        if ($res){
            return true;
        }
        return false;
    }
}