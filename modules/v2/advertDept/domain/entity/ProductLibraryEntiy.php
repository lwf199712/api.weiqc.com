<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: F
 * Date: 2019/10/16
 * Time: 8:48
 */

namespace app\modules\v2\advertDept\domain\entity;


use app\models\dataObject\ProductLibraryDo;
use app\modules\v2\advertDept\domain\dto\ProductLibraryDto;
use app\modules\v2\advertDept\domain\dto\ProductLibraryForm;
use Exception;
use Yii;

class ProductLibraryEntiy extends ProductLibraryDo
{
    /**
     * 根据产品名查找一个产品库实体
     * @param string $productName
     * @return ProductLibraryEntiy|null
     * author: pengguochao
     * Date Time 2019/10/16 9:07
     */
    public function findOneEntiy(string $productName): ?ProductLibraryEntiy
    {
        return self::findOne(['product_name' => $productName]);
    }

    /**
     * 产品库实体详情
     * @param ProductLibraryDto $productLibraryDto
     * @return ProductLibraryEntiy|null
     * author: pengguochao
     * Date Time 2019/10/17 21:04
     */
    public function detailEntity(ProductLibraryDto $productLibraryDto): ?ProductLibraryEntiy
    {
        return self::findOne($productLibraryDto->id);
    }

    /**
     * 创建一个产品库实体
     * @param ProductLibraryForm $productLibraryForm
     * @return bool
     * author: pengguochao
     * Date Time 2019/10/17 21:00
     * @throws Exception
     */
    public function createEntity(ProductLibraryForm $productLibraryForm): ?bool
    {
        $model = new self;
        $model->setAttributes($productLibraryForm->getAttributes());
        $model->create_at = time();
        $model->founder = Yii::$app->user->identity['username'];
        try {
            return $model->save();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry')){
                throw new Exception($productLibraryForm->product_name . '该产品名已存在，请检查好后再重新录入');
            }
            throw new Exception($e->getMessage());
        }

    }

    /**
     * 更新产品库实体
     * @param ProductLibraryForm $productLibraryForm
     * @return bool
     * @throws Exception
     * author: pengguochao
     * Date Time 2019/10/17 21:10
     */
    public function updateEntity(ProductLibraryForm $productLibraryForm): ?bool
    {
        $model = self::findOne($productLibraryForm->id);
        if ($model === null) {
            throw new Exception('找不到这一条记录，不能更新');
        }
        $windowProjectEntiy = new WindowProjectEntiy();
        $isHaveProductName = $windowProjectEntiy->findEntityByProductName($model->product_name);
        if ($isHaveProductName){
            if ($isHaveProductName['product_name'] !== $productLibraryForm->product_name){
                throw new Exception('该产品名称已在橱窗项目有数据，不能修改该产品名称，，请检查好后再重新录入');
            }
        }
        try {
            $model->setAttributes($productLibraryForm->getAttributes());
            return $model->save();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry')){
                throw new Exception($productLibraryForm->product_name . '该产品名已存在，请检查好后再重新录入');
            }
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 删除产品库实体
     * @param ProductLibraryDto $productLibraryDto
     * @return int
     * author: pengguochao
     * Date Time 2019/10/17 21:12
     * @throws Exception
     */
    public function deleteEntity(ProductLibraryDto $productLibraryDto): int
    {
        $model = self::findOne($productLibraryDto->id);
        if ($model === null) {
            throw new Exception('找不到这一条记录，不能删除');
        }
        $windowProjectEntiy = new WindowProjectEntiy();
        $isHaveProductName = $windowProjectEntiy->findEntityByProductName($model->product_name);
        if ($isHaveProductName){
            throw new Exception('该产品名称已在橱窗项目有数据，不能删除');
        }
        return self::deleteAll(['id'=>$productLibraryDto->id]);
    }
}