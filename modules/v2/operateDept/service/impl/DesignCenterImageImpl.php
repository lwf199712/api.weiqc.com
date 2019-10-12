<?php declare(strict_types=1);

namespace app\modules\v2\operateDept\service\impl;

use app\models\dataObject\DesignCenterImageDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageForm;
use app\modules\v2\operateDept\domain\dto\DesignCenterImageQuery;
use app\modules\v2\operateDept\domain\entity\DesignCenterImageEntity;
use app\modules\v2\operateDept\domain\repository\DesignCenterImageDoManager;
use app\modules\v2\operateDept\service\DesignCenterImageService;
use Exception;
use RuntimeException;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class DesignCenterImageImpl extends BaseObject implements DesignCenterImageService
{

    /** @var DesignCenterImageDoManager */
    public $designCenterImageDoManager;
    /** @var DesignCenterImageForm */
    public $designCenterImageForm;
    /** @var DesignCenterImageQuery */
    public $designCenterImageQuery;
    /** @var ActiveRecord */
    public $model;


    public function __construct(
        DesignCenterImageDoManager  $designCenterImageDoManager,
        DesignCenterImageForm       $designCenterImageForm,
        DesignCenterImageQuery      $designCenterImageQuery,
        DesignCenterImageEntity     $designCenterImageEntity,
        $config = [])
    {
        $this->designCenterImageDoManager = $designCenterImageDoManager;
        $this->designCenterImageForm      = $designCenterImageForm;
        $this->designCenterImageQuery     = $designCenterImageQuery;
        $this->model                      = $designCenterImageEntity;
        parent::__construct($config);
    }

    /**
     * 设计中心通用-上传图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @param string $dirName
     * @return bool|string
     * @throws Exception
     * @author zhuozhen
     */
    public function uploadImage(DesignCenterImageForm $designCenterImageForm, string $dirName):string
    {
        return $designCenterImageForm->upload($dirName);
    }

    /**
     * 设计中心通用-查看图片列表
     * @param DesignCenterImageQuery $designCenterImageQuery
     * @return array
     * @author zhuozhen
     */
    public function listImage(DesignCenterImageQuery $designCenterImageQuery): array
    {
        $list['lists'] = $this->designCenterImageDoManager->listDataProvider($designCenterImageQuery)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = end($pictureUrl);
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = array_shift($pictureUrl).'.'.end($pictureTwo);
        }
        $list['totalCount'] = $this->designCenterImageDoManager->listDataProvider($designCenterImageQuery)->getTotalCount();
        return $list;
    }

    /**
     * 设计中心通用-创建图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function createImage(DesignCenterImageForm $designCenterImageForm) : bool
    {
        $result = $this->model->createEntity($designCenterImageForm);
        if ($result === false) {
            throw new RuntimeException('新增设计中心图片失败');
        }
        return $result;
    }

    /**
     * 设计中心通用-更新图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @throws Exception
     * @author zhuozhen
     */
    public function updateImage(DesignCenterImageForm $designCenterImageForm) :bool
    {
        $result = $this->model->updateEntity($designCenterImageForm);
        if ($result === false) {
            throw new RuntimeException('更新设计中心图片失败');
        }
        return $result;
    }

    /**
     * 设计中心通用-删除图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return int
     * @author zhuozhen
     */
    public function deleteImage(DesignCenterImageForm $designCenterImageForm): int
    {
        $result = $this->model->deleteEntity($designCenterImageForm);
        if ($result){
            //如果有删除记录，根据路径删除图片文件
            $dePath = $this->viewImage((int)$designCenterImageForm->id);
            unlink(Yii::$app->basePath . '/web' . $dePath['picture_address']);
        }
        if ($result === false) {
            throw new RuntimeException('删除设计中心图片失败');
        }
        return $result;
    }

    /**
     * 设计中心通用-审核图片
     * @param DesignCenterImageForm $designCenterImageForm
     * @return bool
     * @author zhuozhen
     */
    public function auditImage(DesignCenterImageForm $designCenterImageForm) : bool
    {
        $result = $this->model->auditEntity($designCenterImageForm);
        if ($result === false) {
            throw new RuntimeException('审核设计中心图片失败');
        }
        return $result;
    }

    /**
     * 设计中心通用-查看图片详情
     * @param int $id
     * @return array
     * @author zhuozhen
     */
    public function viewImage(int $id): array
    {
        if (!empty($id)){
            /** @var DesignCenterImageDo $model */
            $model = $this->designCenterImageDoManager->viewData($id);
            return $model->attributes;
        }
        throw new RuntimeException('查看失败');
    }


}