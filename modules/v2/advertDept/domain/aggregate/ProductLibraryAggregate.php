<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/17
 * Time: 20:32
 */

namespace app\modules\v2\advertDept\domain\aggregate;


use app\modules\v2\advertDept\domain\dto\ProductLibraryDto;
use app\modules\v2\advertDept\domain\dto\ProductLibraryForm;
use app\modules\v2\advertDept\domain\entity\ProductLibraryEntiy as ProductLibraryAggregateRoot;
use app\modules\v2\advertDept\domain\repository\ProductLibraryDoManager;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class ProductLibraryAggregate
 * @property ProductLibraryAggregateRoot $productLibraryAggregateRoot
 * @property ProductLibraryDto $productLibraryDto
 * @property ProductLibraryDoManager $productLibraryDoManager
 * @package app\modules\v2\operateDept\domain\aggregate
 */
class ProductLibraryAggregate extends BaseObject
{
    /** @var ProductLibraryAggregateRoot */
    private $productLibraryAggregateRoot;
    /** @var ProductLibraryDto */
    private $productLibraryDto;
    /** @var ProductLibraryDoManager */
    private $productLibraryDoManager;

    public function __construct(
        ProductLibraryAggregateRoot $productLibraryAggregateRoot,
        ProductLibraryDto $productLibraryDto,
        ProductLibraryDoManager $productLibraryDoManager,
        $config = [])
    {
        $this->productLibraryAggregateRoot = $productLibraryAggregateRoot;
        $this->productLibraryDto = $productLibraryDto;
        $this->productLibraryDoManager = $productLibraryDoManager;
        parent::__construct($config);
    }

    /**
     * 创建一个产品库实体
     * @param ProductLibraryForm $productLibraryForm
     * @return bool
     * @throws Exception
     * @throws \Exception
     * author: pengguochao
     * Date Time 2019/10/17 21:17
     */
    public function createProductLibrary(ProductLibraryForm $productLibraryForm): bool
    {
        $result = $this->productLibraryAggregateRoot->createEntity($productLibraryForm);
        if (!$result){
            throw new Exception('新增产品库失败');
        }
        return $result;
    }

    public function listProductLibrary(ProductLibraryDto $productLibraryDto)
    {
        if (isset($productLibraryDto->toArray(['beginTime'])['beginTime'])){
            $productLibraryDto->setAttributes(['beginTime' => strtotime($productLibraryDto->getAttributes(['beginTime'])['beginTime'])]);
        }
        if (isset($productLibraryDto->toArray(['endTime'])['endTime'])){
            $productLibraryDto->setAttributes(['endTime' => strtotime($productLibraryDto->getAttributes(['endTime'])['endTime'])]);
        }
        $data = $this->productLibraryDoManager->listDataProvider($productLibraryDto, ['id' => SORT_DESC])->getModels();
        $i = 0;
        array_map(static function ($value) use (&$i,&$data){
            $data[$i]['create_at'] = date('Y-m-d H:i:s',(int)$value['create_at']);
            $i++;
        },$data);
        $list['lists'] = $data;
        $list['totalCount'] = $this->productLibraryDoManager->listDataProvider($productLibraryDto, ['id' => SORT_DESC])->getTotalCount();
        return $list;
    }

    /**
     * 获取产品库实体详情
     * @param ProductLibraryDto $productLibraryDto
     * @return array
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/17 21:42
     */
    public function detailProductLibrary(ProductLibraryDto $productLibraryDto): array
    {
        $detailProductLibrary = $this->productLibraryAggregateRoot->detailEntity($productLibraryDto);
        if (!$detailProductLibrary){
            throw new Exception('查看详情失败，或者找不到该产品库信息');
        }
        return $detailProductLibrary->attributes;
    }

    /**
     * 更新产品库实体
     * @param ProductLibraryForm $productLibraryForm
     * @return bool|null
     * @throws Exception
     * @throws \Exception
     * author: pengguochao
     * Date Time 2019/10/18 8:44
     */
    public function updateProductLibrary(ProductLibraryForm $productLibraryForm): ?bool
    {
        $result = $this->productLibraryAggregateRoot->updateEntity($productLibraryForm);
        if (!$result){
            throw new Exception('更新产品库失败');
        }
        return $result;
    }

    /**
     * 删除产品库实体
     * @param ProductLibraryDto $productLibraryDto
     * @return int
     * author: pengguochao
     * Date Time 2019/10/18 8:48
     * @throws \Exception
     */
    public function deleteProductLibrary(ProductLibraryDto $productLibraryDto): int
    {
        return $this->productLibraryAggregateRoot->deleteEntity($productLibraryDto);
    }
}