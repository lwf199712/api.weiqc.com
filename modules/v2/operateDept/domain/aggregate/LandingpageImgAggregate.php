<?php

namespace app\modules\v2\operateDept\domain\aggregate;

use app\modules\v2\operateDept\domain\dto\LandingpageImgDto;
use app\modules\v2\operateDept\domain\dto\LandingpageImgForm;
use app\modules\v2\operateDept\domain\repository\LandingpageImgDoManager;
use app\modules\v2\operateDept\domain\entity\LandingpageImgEntity as LandingpageImgAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class LandingpageImgAggregate
 * @property LandingpageImgDto             $landingpageImgDto
 * @property LandingpageImgDoManager       $landingpageImgDoManager
 * @property LandingpageImgAggregateRoot   $landingpageImgAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class LandingpageImgAggregate extends BaseObject
{
    private $landingpageImgAggregateRoot;
    /** @var LandingpageImgDto */
    private $landingpageImgDto;
    /** @var LandingpageImgDoManager */
    private $landingpageImgDoManager;

    public function __construct(
        LandingpageImgDto              $landingpageImgDto,
        LandingpageImgDoManager        $landingpageImgDoManager,
        LandingpageImgAggregateRoot    $landingpageImgAggregateRoot,
        $config = [])
    {
        $this->landingpageImgDto              = $landingpageImgDto;
        $this->landingpageImgDoManager        = $landingpageImgDoManager;
        $this->landingpageImgAggregateRoot    = $landingpageImgAggregateRoot;
        parent::__construct($config);
    }

    /**
     * @param LandingpageImgDto $LandingpageImgDto
     * @return array
     * @author weifeng
     */
    public function listLandingpageImg(LandingpageImgDto $LandingpageImgDto): array
    {
        $list['lists'] = $this->landingpageImgDoManager->listDataProvider($LandingpageImgDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[4];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->landingpageImgDoManager->listDataProvider($LandingpageImgDto)->getTotalCount();
        return $list;
    }

    /**
     * @param LandingpageImgForm $landingpageImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function createLandingpageImg(LandingpageImgForm $landingpageImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $landingpageImgForm->imageFile = $imageFile;
        $result = $this->landingpageImgAggregateRoot->createEntity($landingpageImgForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param LandingpageImgForm $landingpageImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateLandingpageImg(LandingpageImgForm $landingpageImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $landingpageImgForm->imageFile = $imageFile;
        $status = $this->landingpageImgAggregateRoot->detailEntity((int)$landingpageImgForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->landingpageImgAggregateRoot->updateEntity($landingpageImgForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $landingpageImgId
     * @return int
     * @author weifeng
     */
    public function deleteLandingpageImg(int $landingpageImgId): int
    {
        return $this->landingpageImgAggregateRoot->deleteEntity($landingpageImgId);
    }

    /**
     *
     * @param LandingpageImgDto $landingpageImgDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditLandingpageImg(LandingpageImgDto $landingpageImgDto): bool
    {
        $result = $this->landingpageImgAggregateRoot->auditEntity($landingpageImgDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $landingpageImgId
     * @return string
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function readLandingpageImg(int $landingpageImgId): string
    {
        return $this->landingpageImgAggregateRoot->readEntity($landingpageImgId);
    }

    /**
     *
     * @param int $landingpageImgId
     * @return array
     * @author: weifeng
     */

    public function detailLandingpageImg(int $landingpageImgId): array
    {
        return $this->landingpageImgAggregateRoot->detailEntity($landingpageImgId);
    }

}