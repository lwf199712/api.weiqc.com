<?php

namespace app\modules\v2\operateDept\domain\aggregate;

use app\modules\v2\operateDept\domain\dto\ZuanzhanImgDto;
use app\modules\v2\operateDept\domain\dto\ZuanzhanImgForm;
use app\modules\v2\operateDept\domain\repository\ZuanzhanImgDoManager;
use app\modules\v2\operateDept\domain\entity\ZuanzhanImgEntity as ZuanzhanImgAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class ZuanzhanImgAggregate
 * @property ZuanzhanImgDto             $zuanzhanImgDto
 * @property ZuanzhanImgDoManager       $zuanzhanImgDoManager
 * @property ZuanzhanImgAggregateRoot   $zuanzhanImgAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class ZuanzhanImgAggregate extends BaseObject
{
    private $zuanzhanImgAggregateRoot;
    /** @var ZuanzhanImgDto */
    private $zuanzhanImgDto;
    /** @var ZuanzhanImgDoManager */
    private $zuanzhanImgDoManager;

    public function __construct(
        ZuanzhanImgDto              $zuanzhanImgDto,
        ZuanzhanImgDoManager        $zuanzhanImgDoManager,
        ZuanzhanImgAggregateRoot    $zuanzhanImgAggregateRoot,
        $config = [])
    {
        $this->zuanzhanImgDto              = $zuanzhanImgDto;
        $this->zuanzhanImgDoManager        = $zuanzhanImgDoManager;
        $this->zuanzhanImgAggregateRoot    = $zuanzhanImgAggregateRoot;
        parent::__construct($config);
    }

    /**
     * @param ZuanzhanImgDto $ZuanzhanImgDto
     * @return array
     * @author weifeng
     */
    public function listZuanzhanImg(ZuanzhanImgDto $ZuanzhanImgDto): array
    {
        $list['lists'] = $this->zuanzhanImgDoManager->listDataProvider($ZuanzhanImgDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[3];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->zuanzhanImgDoManager->listDataProvider($ZuanzhanImgDto)->getTotalCount();
        return $list;
    }

    /**
     * @param ZuanzhanImgForm $zuanzhanImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function createZuanzhanImg(ZuanzhanImgForm $zuanzhanImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $zuanzhanImgForm->imageFile = $imageFile;
        $result = $this->zuanzhanImgAggregateRoot->createEntity($zuanzhanImgForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param ZuanzhanImgForm $zuanzhanImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateZuanzhanImg(ZuanzhanImgForm $zuanzhanImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $zuanzhanImgForm->imageFile = $imageFile;
        $status = $this->zuanzhanImgAggregateRoot->detailEntity((int)$zuanzhanImgForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->zuanzhanImgAggregateRoot->updateEntity($zuanzhanImgForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $zuanzhanImgId
     * @return int
     * @author weifeng
     */
    public function deleteZuanzhanImg(int $zuanzhanImgId): int
    {
        return $this->zuanzhanImgAggregateRoot->deleteEntity($zuanzhanImgId);
    }

    /**
     *
     * @param ZuanzhanImgDto $zuanzhanImgDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditZuanzhanImg(ZuanzhanImgDto $zuanzhanImgDto): bool
    {
        $result = $this->zuanzhanImgAggregateRoot->auditEntity($zuanzhanImgDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $zuanzhanImgId
     * @return string
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function readZuanzhanImg(int $zuanzhanImgId): string
    {
        return $this->zuanzhanImgAggregateRoot->readEntity($zuanzhanImgId);
    }

    /**
     *
     * @param int $zuanzhanImgId
     * @return array
     * @author: weifeng
     */

    public function detailZuanzhanImg(int $zuanzhanImgId): array
    {
        return $this->zuanzhanImgAggregateRoot->detailEntity($zuanzhanImgId);
    }

}