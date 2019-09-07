<?php
declare(strict_types=1);

namespace app\modules\v2\operateDept\domain\entity;

use app\models\dataObject\DesignCenterDo;
use app\modules\v2\operateDept\domain\dto\DesignCenterDto;
use app\modules\v2\operateDept\domain\dto\DesignCenterForm;
use Yii;
use yii\db\Exception;

class DesignCenterEntity extends DesignCenterDo
{

    /**
     * 创建设计中心实体
     * @param DesignCenterForm $designCenterForm
     * @return bool
     * @author weifeng
     */
    public function createEntity(DesignCenterForm $designCenterForm): bool
    {
        $model = new self;
        $model->setAttributes($designCenterForm->getAttributes());
        if ($designCenterForm->upload()){
            $model->picture_address = '/uploads/designCenter/' . $designCenterForm->imageFile->baseName . '.' . $designCenterForm->imageFile->extension;
            $model->upload_time = time();
            $model->audit_status = 0;
        }
         return $model->save();
    }

    /**
     * 更新设计中心实体
     * @param DesignCenterForm $designCenterForm
     * @return bool
     * @throws Exception
     * @author weifeng
     */
    public function updateEntity(DesignCenterForm $designCenterForm): bool
    {
        $model = self::findOne($designCenterForm->id);
        if ($model === null) {
            throw new Exception('找不到修改的数据');
        }
        $model->setAttributes($designCenterForm->getAttributes());
        if($designCenterForm->upload()){
            $model->picture_address = '/uploads/designCenter/' . $designCenterForm->imageFile->baseName . '.' . $designCenterForm->imageFile->extension;
            $model->upload_time = time();
        }
        return $model->save();
    }

    /**
     * 删除设计中心实体
     * @param int $id
     * @return int
     * @author weifeng
     */
    public function deleteEntity(int $id): int
    {
        return self::deleteAll(['id' => $id]);
    }

    /**
     *
     * @param DesignCenterDto $designCenterDto
     * @return bool
     * @author: weifeng
     * @Date: 2019/9/6
     */

    public function auditEntity(DesignCenterDto $designCenterDto): bool
    {
        $model = self::findOne($designCenterDto->id);
        $model->setAttributes($designCenterDto->toArray(),false);
        return $model->save();
    }

    /**
     * 查看设计中心图片实体
     * @param int $id
     * @return string
     */
    public function readEntity(int $id): string
    {
        $model = self::findOne($id);
        return $model->picture_address;
    }

}