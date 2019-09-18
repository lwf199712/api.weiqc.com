<?php

namespace app\modules\v2\operateDept\domain\aggregate;

use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use app\modules\v2\operateDept\domain\dto\DesignCenterForm;
use app\modules\v2\operateDept\domain\repository\DesignCenterDoManager;
use app\modules\v2\operateDept\domain\entity\DesignCenterEntity as DesignCenterAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class DesignCenterAggregate
 * @property DesignCenterDto             $designCenterDto
 * @property DesignCenterDoManager       $designCenterDoManager
 * @property DesignCenterAggregateRoot   $designCenterAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class DesignCenterAggregate extends BaseObject
{
    private $designCenterAggregateRoot;
    /** @var DesignCenterDto */
    private $designCenterDto;
    /** @var DesignCenterDoManager */
    private $designCenterDoManager;

    public function __construct(
        DesignCenterDto              $designCenterDto,
        DesignCenterDoManager        $designCenterDoManager,
        DesignCenterAggregateRoot    $designCenterAggregateRoot,
        $config = [])
    {
        $this->designCenterDto              = $designCenterDto;
        $this->designCenterDoManager        = $designCenterDoManager;
        $this->designCenterAggregateRoot    = $designCenterAggregateRoot;
        parent::__construct($config);
    }

    /**
     * @param DesignCenterDto $DesignCenterDto
     * @return array
     * @author weifeng
     */
    public function listDesignCenter(DesignCenterDto $DesignCenterDto): array
    {
        $list['lists'] = $this->designCenterDoManager->listDataProvider($DesignCenterDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[3];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->designCenterDoManager->listDataProvider($DesignCenterDto)->getTotalCount();
        return $list;
    }

    /**
     * @param DesignCenterForm $designCenterForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function createDesignCenter(DesignCenterForm $designCenterForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $designCenterForm->imageFile = $imageFile;
        $result = $this->designCenterAggregateRoot->createEntity($designCenterForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param DesignCenterForm $designCenterForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateDesignCenter(DesignCenterForm $designCenterForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $designCenterForm->imageFile = $imageFile;
        $status = $this->designCenterAggregateRoot->detailEntity((int)$designCenterForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->designCenterAggregateRoot->updateEntity($designCenterForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $designCenterId
     * @return int
     * @author weifeng
     */
    public function deleteDesignCenter(int $designCenterId): int
    {
        return $this->designCenterAggregateRoot->deleteEntity($designCenterId);
    }

    /**
     *
     * @param DesignCenterDto $designCenterDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditDesignCenter(DesignCenterDto $designCenterDto): bool
    {
        $result = $this->designCenterAggregateRoot->auditEntity($designCenterDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $designCenterId
     * @return string
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function readDesignCenter(int $designCenterId): string
    {
        return $this->designCenterAggregateRoot->readEntity($designCenterId);
    }

    /**
     *
     * @param int $designCenterId
     * @return array
     * @author: weifeng
     */

    public function detailDesignCenter(int $designCenterId): array
    {
        return $this->designCenterAggregateRoot->detailEntity($designCenterId);
    }

}