<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\ProductdetailImgDo;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgDto;
use app\modules\v2\operateDept\domain\dto\ProductdetailImgForm;
use Yii;
use yii\db\Exception;

class ProductdetailImgEntity extends ProductdetailImgDo
{

    /**
     * 创建设计中心实体
     * @param ProductdetailImgForm $productdetailImgForm
     * @return bool
     * @author ctl
     */
    public function createEntity(ProductdetailImgForm $productdetailImgForm): bool
    {
        $model = new self;
        $model->setAttributes($productdetailImgForm->getAttributes());
        if ($picture_address = $productdetailImgForm->upload()) {
            $model->picture_address = '/uploads/designCenter/product-detail-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 更新设计中心实体
     * @param ProductdetailImgForm $productdetailImgForm
     * @return bool
     * @throws Exception
     * @author: ctl
     */

    public function updateEntity(ProductdetailImgForm $productdetailImgForm): bool
    {
        $model = self::findOne($productdetailImgForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($productdetailImgForm->getAttributes());
        if($picture_address = $productdetailImgForm->upload()){
            $model->picture_address = '/uploads/designCenter/product-detail-img/' . $picture_address;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
        return $model->save();
    }

    /**
     * 删除设计中心实体
     * @param int $id
     * @return int
     * @author: ctl
     */
    public function deleteEntity(int $id): int
    {
        $delPath = $this->detailEntity($id);
        unlink(Yii::$app->basePath . '/web'.$delPath['picture_address'] );
        return self::deleteAll(['id' => $id]);
    }

    /**
     *
     * @param ProductdetailImgDto $productdetailImgDto
     * @return bool
     * @author: ctl
     */

    public function auditEntity(ProductdetailImgDto $productdetailImgDto): bool
    {
        $model = self::findOne($productdetailImgDto->id);
        $model->setAttributes($productdetailImgDto->toArray(),false);
        $model->audit_time = time();
        return $model->save();
    }

    /**
     * 查看设计中心图片实体
     * @param int $id
     * @return string
     * @author: ctl
     */
    public function readEntity(int $id): string
    {
        $model = self::findOne($id);
        return $model->picture_address;
    }

    /**
     * 设计中心详情实体
     * @param int $id
     * @return array
     * @author: ctl
     */

    public function detailEntity(int $id): array
    {
        $model = self::findOne($id);
        return $model->attributes;
    }



}