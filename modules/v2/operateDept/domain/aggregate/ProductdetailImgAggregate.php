<?php

namespace app\modules\v2\operateDept\domain\aggregate;

use app\modules\v2\operateDept\domain\dto\ProductdetailImgDto;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgForm;
use app\modules\v2\operateDept\domain\repository\ProductdetailImgDoManager;
use app\modules\v2\operateDept\domain\entity\ProductdetailImgEntity as ProductdetailImgAggregateRoot;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class ProductdetailImgAggregate
 * @property ProductdetailImgDto             $productdetailImgDto
 * @property ProductdetailImgDoManager       $productdetailImgDoManager
 * @property ProductdetailImgAggregateRoot   $productdetailImgAggregateRoot
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class ProductdetailImgAggregate extends BaseObject
{
    private $productdetailImgAggregateRoot;
    /** @var ProductdetailImgDto */
    private $productdetailImgDto;
    /** @var ProductdetailImgDoManager */
    private $productdetailImgDoManager;

    public function __construct(
        ProductdetailImgDto              $productdetailImgDto,
        ProductdetailImgDoManager        $productdetailImgDoManager,
        ProductdetailImgAggregateRoot    $productdetailImgAggregateRoot,
        $config = [])
    {
        $this->productdetailImgDto              = $productdetailImgDto;
        $this->productdetailImgDoManager        = $productdetailImgDoManager;
        $this->productdetailImgAggregateRoot    = $productdetailImgAggregateRoot;
        parent::__construct($config);
    }

    /**
     * @param ProductdetailImgDto $ProductdetailImgDto
     * @return array
     * @author weifeng
     */
    public function listProductdetailImg(ProductdetailImgDto $ProductdetailImgDto): array
    {
        $list['lists'] = $this->productdetailImgDoManager->listDataProvider($ProductdetailImgDto)->getModels();
        foreach ($list['lists'] as $key => $value) {
            $list['lists'][$key]['picture_address'] = Yii::$app->request->getHostInfo() . $value['picture_address'];
            $pictureUrl = explode('/', $value['picture_address']);
            $picture = $pictureUrl[4];
            $pictureUrl = explode('_', $picture);
            $pictureTwo = explode('.', $picture);
            $list['lists'][$key]['picture_name'] = $pictureUrl[0].'.'.$pictureTwo[1];
        }
        $list['totalCount'] = $this->productdetailImgDoManager->listDataProvider($ProductdetailImgDto)->getTotalCount();
        return $list;
    }

    /**
     * @param ProductdetailImgForm $productdetailImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function createProductdetailImg(ProductdetailImgForm $productdetailImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $productdetailImgForm->imageFile = $imageFile;
        $result = $this->productdetailImgAggregateRoot->createEntity($productdetailImgForm);
        if ($result === false) {
            throw new Exception('新增设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param ProductdetailImgForm $productdetailImgForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateProductdetailImg(ProductdetailImgForm $productdetailImgForm): bool
    {
        $imageFile  = UploadedFile::getInstanceByName('imageFile');
        $productdetailImgForm->imageFile = $imageFile;
        $status = $this->productdetailImgAggregateRoot->detailEntity((int)$productdetailImgForm->id);
        if ($status['audit_status'] === 1){
            throw new Exception('审核状态为已通过');
        }
        $result = $this->productdetailImgAggregateRoot->updateEntity($productdetailImgForm);
        if ($result === false) {
            throw new Exception('更新设计中心核实失败');
        }
        return $result;
    }

    /**
     * @param int $productdetailImgId
     * @return int
     * @author weifeng
     */
    public function deleteProductdetailImg(int $productdetailImgId): int
    {
        return $this->productdetailImgAggregateRoot->deleteEntity($productdetailImgId);
    }

    /**
     *
     * @param ProductdetailImgDto $productdetailImgDto
     * @return bool
     * @throws Exception
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditProductdetailImg(ProductdetailImgDto $productdetailImgDto): bool
    {
        $result = $this->productdetailImgAggregateRoot->auditEntity($productdetailImgDto);
        if ($result === false) {
            throw new Exception('审核失败');
        }
        return $result;
    }

    /**
     *
     * @param int $productdetailImgId
     * @return string
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function readProductdetailImg(int $productdetailImgId): string
    {
        return $this->productdetailImgAggregateRoot->readEntity($productdetailImgId);
    }

    /**
     *
     * @param int $productdetailImgId
     * @return array
     * @author: weifeng
     */

    public function detailProductdetailImg(int $productdetailImgId): array
    {
        return $this->productdetailImgAggregateRoot->detailEntity($productdetailImgId);
    }

}