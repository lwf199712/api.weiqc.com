<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\service\impl;


use app\common\repository\BaseRepository;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageForm;
use app\modules\v2\operateDept\domain\repository\DesignCenterDoManager;
use app\modules\v2\operateDept\service\DesignCenterImageService;
use Exception;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class DesignCenterImageImpl extends BaseObject implements DesignCenterImageService
{

    /** @var DesignCenterDoManager */
    public $designCenterDoManager;
    /** @var ActiveRecord */
    public $model;
    /** @var BaseRepository */
    public $repository;


    public function __construct(
        DesignCenterDoManager $designCenterDoManager,
        $config = [])
    {
        $this->designCenterDoManager = $designCenterDoManager;
        parent::__construct($config);
    }

    public function __call($name, $params)
    {
        $this->model = current($params);
        $params = array_slice($params,1);
        $this->$name($this->model,...$params);
    }


    /**
     * 设计中心通用-上传图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @param string                $dirName
     * @return bool|string
     * @throws Exception
     * @author zhuozhen
     */
    public function uploadImage(DesignCenterImageForm $designCenterImageForm, string $dirName)
    {
        return $designCenterImageForm->upload($dirName);
    }

    /**
     * 设计中心通用-查看图片列表
     * @author zhuozhen
     */
    public function listImage()
    {
        return $this->$repository->listDataProvider();
    }

    /**
     * 设计中心通用-查看图片详情
     * @param int $id
     * @author zhuozhen
     */
    public function viewImage(int $id)
    {
        $this->designCenterDoManager->viewData($id);
    }

    /**
     * 设计中心通用-创建图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @author zhuozhen
     */
    public function createImage(DesignCenterImageForm $designCenterImageForm )
    {
        $designCenterImageForm->getAttributes();
    }

    /**
     * 设计中心通用-更新图片
     * @author zhuozhen
     */
    public function updateImage()
    {

    }

    /**
     * 设计中心通用-删除图片
     * @author zhuozhen
     */
    public function deleteImage()
    {

    }

    /**
     * 设计中心通用-审核图片
     * @author zhuozhen
     */
    public function audilImage()
    {

    }


}