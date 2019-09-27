<?php

namespace app\modules\v2\operateDept\domain\aggregate;

use app\modules\v2\operateDept\domain\dto\ZhitongcheImgDto;
use app\modules\v2\operateDept\domain\dto\ZhitongcheImgForm;
use app\modules\v2\operateDept\domain\repository\ZhitongcheImgDoManager;
use app\modules\v2\operateDept\domain\entity\ZhitongcheImgEntity as ZhitongcheImgAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class ZhitongcheImgAggregate
 * @property ZhitongcheImgDto             $zhitongcheImgDto
 * @property ZhitongcheImgDoManager       $zhitongcheImgDoManager
 * @property ZhitongcheImgAggregateRoot   $zhitongcheImgAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class ZhitongcheImgAggregate extends BaseObject
{
    private $zhitongcheImgAggregateRoot;
    /** @var ZhitongcheImgDto */
    private $zhitongcheImgDto;
    /** @var ZhitongcheImgDoManager */
    private $zhitongcheImgDoManager;

    public function __construct(
        ZhitongcheImgDto              $zhitongcheImgDto,
        ZhitongcheImgDoManager        $zhitongcheImgDoManager,
        ZhitongcheImgAggregateRoot    $zhitongcheImgAggregateRoot,
        $config = [])
    {
        $this->zhitongcheImgDto              = $zhitongcheImgDto;
        $this->zhitongcheImgDoManager        = $zhitongcheImgDoManager;
        $this->zhitongcheImgAggregateRoot    = $zhitongcheImgAggregateRoot;
        parent::__construct($config);
    }

    /**
     * @param ZhitongcheImgDto $ZhitongcheImgDto
     * @return array
     * @author weifeng
     */
    public function listZhitongcheImg(ZhitongcheImgDto $ZhitongcheImgDto): array
    {
        $list['lists'] = $this->zhitongcheImgDoManager->listDataProvider($ZhitongcheImgDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[3];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->zhitongcheImgDoManager->listDataProvider($ZhitongcheImgDto)->getTotalCount();
        return $list;
    }

    /**
     * @param ZhitongcheImgForm $zhitongcheImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function createZhitongcheImg(ZhitongcheImgForm $zhitongcheImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $zhitongcheImgForm->imageFile = $imageFile;
        $result = $this->zhitongcheImgAggregateRoot->createEntity($zhitongcheImgForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param ZhitongcheImgForm $zhitongcheImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateZhitongcheImg(ZhitongcheImgForm $zhitongcheImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $zhitongcheImgForm->imageFile = $imageFile;
        $status = $this->zhitongcheImgAggregateRoot->detailEntity((int)$zhitongcheImgForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->zhitongcheImgAggregateRoot->updateEntity($zhitongcheImgForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $zhitongcheImgId
     * @return int
     * @author weifeng
     */
    public function deleteZhitongcheImg(int $zhitongcheImgId): int
    {
        return $this->zhitongcheImgAggregateRoot->deleteEntity($zhitongcheImgId);
    }

    /**
     *
     * @param ZhitongcheImgDto $zhitongcheImgDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditZhitongcheImg(ZhitongcheImgDto $zhitongcheImgDto): bool
    {
        $result = $this->zhitongcheImgAggregateRoot->auditEntity($zhitongcheImgDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $zhitongcheImgId
     * @return string
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function readZhitongcheImg(int $zhitongcheImgId): string
    {
        return $this->zhitongcheImgAggregateRoot->readEntity($zhitongcheImgId);
    }

    /**
     *
     * @param int $zhitongcheImgId
     * @return array
     * @author: weifeng
     */

    public function detailZhitongcheImg(int $zhitongcheImgId): array
    {
        return $this->zhitongcheImgAggregateRoot->detailEntity($zhitongcheImgId);
    }

}