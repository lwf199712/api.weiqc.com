<?php
/**
 * Created by: FengDaJin
 * Date: 2018/9/25
 */
namespace app\components;

//use OSS\OssClient;
use OSS\Core\OssException;

/**
 * Class Oss
 * @package components
 */
class Oss
{
    private $accessKeyId = 'LTAIKdKWUrWZrXpQ';
    private $accessKeySecret = 'gx8zUqjRbMm5iuquU2YPZM12ltiK6z';
    private $endpoint = 'oss-cn-hangzhou.aliyuncs.com';
    private $bucket = 'fandow';

    public function __construct($accessKeyId = '', $accessKeySecret = '', $endpoint = '', $bucket = '')
    {
        if (! empty($accessKeyId)) {
            $this->accessKeyId = $accessKeyId;
        }
        if (! empty($accessKeySecret)) {
            $this->accessKeySecret = $accessKeySecret;
        }
        if (! empty($endpoint)) {
            $this->endpoint = $endpoint;
        }
        if (! empty($endpoint)) {
            $this->bucket = $bucket;
        }
    }

    /**
     * @param $savePath
     * @param $file
     * @return mixed
     * @throws \OSS\Core\OssException
     * @author FengDaJin
     * @date 2018/9/25
     */
    public function saveToOss($savePath, $file)
    {
        $ossClient = new \OSS\OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        $result = $ossClient->uploadFile($this->bucket, $savePath, $file);
        return $result['info']['url'];
    }


    /**
     * delete oss file
     * Date: 2019/11/1
     * Author: ctl
     * @param $ossObject
     * @return bool
     * @throws OssException
     */
    public function deleteToOss($ossObject): bool
    {
        $oss =new \OSS\OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        $res = $oss->doesObjectExist($this->bucket,$ossObject);
        if (!$res){
            throw new OssException('文件不存在');
        }
        try{
            $oss->deleteObject($this->bucket,$ossObject);
            return true;
        } catch(OssException $e) {
            return false;
        }
    }
}